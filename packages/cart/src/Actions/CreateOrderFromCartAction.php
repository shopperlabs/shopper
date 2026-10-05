<?php

declare(strict_types=1);

namespace Shopper\Cart\Actions;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Shopper\Cart\CartManager;
use Shopper\Cart\Discounts\DiscountValidator;
use Shopper\Cart\Events\CartCompleted;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\DiscountLimitReachedException;
use Shopper\Cart\Exceptions\InsufficientStockException;
use Shopper\Cart\Exceptions\PromotionUnavailableException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartAddress;
use Shopper\Cart\Models\CartPromotion;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Actions\ReserveCampaignBudget;
use Shopper\Core\Contracts\StockReserver;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Exceptions\CampaignBudgetExceededException;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\Contracts\Stockable;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\OrderAddress;
use Shopper\Core\Models\OrderPromotion;
use Shopper\Core\Models\OrderTaxLine;
use Throwable;

final readonly class CreateOrderFromCartAction
{
    public function __construct(
        private CartManager $cartManager,
        private DiscountValidator $discountValidator,
        private ReserveCampaignBudget $reserveCampaignBudget,
        private StockReserver $stockReserver,
    ) {}

    /**
     * @param  Closure(CartPipelineContext): void|null  $assertTotals  Runs against
     *                                                                 the totals computed under the cart lock, right before the order
     *                                                                 freezes them. Throw to abort: the transaction rolls back.
     * @param  Closure(Order): void|null  $afterCreate  Runs on the created order inside
     *                                                  the same transaction, so anything it persists
     *                                                  (like the payment reference) commits or rolls
     *                                                  back atomically with the order.
     * @param  (Closure(): bool)|null  $honoursPayment
     *
     * @throws Throwable
     */
    public function execute(Cart $cart, ?Closure $assertTotals = null, ?Closure $afterCreate = null, ?Closure $honoursPayment = null): Order
    {
        if ($this->takesForeignKeySharedLocks() && ! $cart->isCompleted() && ! $cart->holdsProviderPaymentSession() && $this->mayAttachAutomaticPromotion($cart)) {
            $this->cartManager->calculate($cart);
        }

        return DB::transaction(function () use ($cart, $assertTotals, $afterCreate, $honoursPayment): Order {
            $cart->setRawAttributes(
                $cart->newQueryWithoutScopes()->lockForUpdate()->findOrFail($cart->getKey())->getAttributes(),
                true,
            );
            $cart->unsetRelations();

            if ($cart->isCompleted()) {
                throw new CartCompletedException;
            }

            $this->lockPromotionCounters($cart);

            $this->cartManager->revalidate($cart, $honoursPayment);

            $context = $this->cartManager->calculate($cart);

            if ($assertTotals) {
                $assertTotals($context);
            }

            $applied = $cart->promotions
                ->where('computed_amount', '>', 0)
                ->sortBy('sequence')
                ->values();

            $honoured = fn (): bool => $honoursPayment !== null && $honoursPayment();
            $discounts = $this->assertPromotionTerms($applied, $context, $honoured);

            $primary = $applied->sortByDesc('computed_amount')->first();
            $primaryDiscount = $primary === null ? null : $discounts[$primary->id];

            $shippingAddress = $this->createOrderAddress($cart->shippingAddress(), $cart->customer_id);
            $billingAddress = $this->createOrderAddress($cart->billingAddress(), $cart->customer_id);

            $order = resolve(Order::class)::query()->create([
                'price_amount' => $context->total,
                'tax_amount' => $context->taxTotal,
                'shipping_amount' => $cart->shipping_amount,
                'currency_code' => $cart->currency_code,
                'email' => $cart->email,
                'customer_id' => $cart->customer_id,
                'channel_id' => $cart->channel_id,
                'zone_id' => $cart->zone_id,
                'payment_method_id' => $cart->payment_method_id,
                'shipping_option_id' => $this->resolveCarrierOptionId($cart->shipping_option_id),
                'shipping_address_id' => $shippingAddress?->id,
                'billing_address_id' => $billingAddress?->id,
                'discount_id' => $primaryDiscount?->id,
                'discount_code' => $primary?->code,
                'discount_type' => ($primary->type ?? $primaryDiscount?->type)?->value,
                'discount_value_at_apply' => $primary->value ?? $primaryDiscount?->value,
                'discount_currency_code' => $applied->isNotEmpty() ? $cart->currency_code : null,
            ]);

            $cart->lines->loadMorph('purchasable', [
                resolve(ProductVariant::class)::class => ['product'],
            ]);

            $cart->lines->load('taxLines');
            $orderTaxLines = [];

            $lines = $cart->lines->sortBy([
                ['purchasable_type', 'asc'],
                ['purchasable_id', 'asc'],
            ])->values();

            foreach ($lines as $line) {
                $discountAmount = $line->adjustments->sum('amount');
                $purchasable = $line->purchasable;
                $taxLines = $line->taxLines;

                $item = $order->items()->create([
                    'name' => $this->resolveItemName($purchasable),
                    'sku' => $purchasable->sku ?? '',
                    'quantity' => $line->quantity,
                    'unit_price_amount' => $line->unit_price_amount,
                    'pricing' => $line->pricing,
                    'metadata' => $line->metadata,
                    'discount_amount' => $discountAmount,
                    'tax_amount' => (int) $taxLines->sum('amount'),
                    'product_type' => $line->purchasable_type,
                    'product_id' => $line->purchasable_id,
                ]);

                foreach ($taxLines as $taxLine) {
                    $orderTaxLines[] = [
                        'taxable_type' => $item->getMorphClass(),
                        'taxable_id' => $item->id,
                        'code' => $taxLine->code,
                        'name' => $taxLine->name,
                        'rate' => $taxLine->rate,
                        'amount' => $taxLine->amount,
                        'tax_rate_id' => $taxLine->tax_rate_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if ($purchasable instanceof Stockable && $purchasable->tracksInventory()) {
                    $reserved = $this->stockReserver->reserve(
                        $purchasable,
                        $line->quantity,
                        $order,
                        $cart->customer_id,
                    );

                    if ($reserved < $line->quantity) {
                        throw new InsufficientStockException($purchasable, $reserved, $line->quantity);
                    }
                }
            }

            if ($orderTaxLines !== []) {
                OrderTaxLine::query()->insert($orderTaxLines);
            }

            $this->reservePromotions($applied, $discounts, $cart, $order, $honoured);

            $order->refresh();

            if ($afterCreate) {
                $afterCreate($order);
            }

            $cart->update([
                'completed_at' => now(),
                'order_id' => $order->id,
            ]);

            CartCompleted::dispatch($cart, $order);

            return $order;
        }, 3);
    }

    private function resolveCarrierOptionId(?string $shippingOptionId): ?int
    {
        if (! $shippingOptionId || ! str_contains($shippingOptionId, ':')) {
            return null;
        }

        [, $serviceCode] = explode(':', $shippingOptionId, 2);

        return CarrierOption::query()->where('public_id', $serviceCode)->value('id');
    }

    /**
     * @param  Collection<int, CartPromotion>  $applied
     * @param  Closure(): bool  $honoured
     * @return array<int, Discount|null>
     */
    private function assertPromotionTerms(Collection $applied, CartPipelineContext $context, Closure $honoured): array
    {
        $discounts = [];

        foreach ($applied as $promotion) {
            // No lockForUpdate: lockPromotionCounters() already holds these rows on
            // MySQL and MariaDB, the conditional total_use increment is atomic on
            // its own, the per-user check is a soft guard, and type/value come from
            // the cart's snapshot.
            $discount = Discount::query()
                ->whereKey($promotion->discount_id)
                ->first();

            if ($discount === null && ! $honoured()) {
                throw new PromotionUnavailableException(__('shopper-cart::messages.discount.not_active'));
            }

            if ($discount !== null) {
                $terms = $this->discountValidator->validateTerms($discount, $context);

                if (! $terms->valid && ! $honoured()) {
                    throw new PromotionUnavailableException((string) $terms->failureReason);
                }
            }

            $discounts[$promotion->id] = $discount;
        }

        return $discounts;
    }

    /**
     * Reserve and snapshot every applied promotion once the order exists
     *
     * @param  Collection<int, CartPromotion>  $applied
     * @param  array<int, Discount|null>  $discounts
     * @param  Closure(): bool  $honoured
     */
    private function reservePromotions(Collection $applied, array $discounts, Cart $cart, Order $order, Closure $honoured): void
    {
        $campaignTotals = [];
        $campaigns = [];

        foreach ($applied as $promotion) {
            $discount = $discounts[$promotion->id];
            $campaignId = $promotion->type !== null ? $promotion->campaign_id : $discount?->campaign_id;
            $campaign = match (true) {
                $campaignId === null => null,
                $campaignId === $discount?->campaign_id => $discount->campaign,
                default => Campaign::query()->find($campaignId),
            };

            if ($campaign !== null) {
                $campaignTotals[$campaign->id] = ($campaignTotals[$campaign->id] ?? 0) + $promotion->computed_amount;
                $campaigns[$campaign->id] = $campaign;
            }

            if ($discount === null) {
                if ($promotion->type !== null) {
                    $order->promotions()->create([
                        'code' => $promotion->code,
                        'type' => $promotion->type->value,
                        'value_at_apply' => $promotion->value,
                        'amount' => $promotion->computed_amount,
                        'currency_code' => $cart->currency_code,
                    ]);
                }

                continue;
            }

            if ($discount->usage_limit_per_user) {
                $column = $cart->customer_id !== null ? 'customer_id' : 'email';
                $value = $cart->customer_id ?? $cart->email;

                $alreadyRedeemed = $value !== null && OrderPromotion::query()
                    ->where('discount_id', $discount->id)
                    ->whereHas('order', fn (Builder $query) => $query->where($column, $value))
                    ->exists();

                if ($alreadyRedeemed && ! $honoured()) {
                    throw DiscountLimitReachedException::perUser($discount->code);
                }
            }

            $affected = Discount::query()
                ->whereKey($discount->id)
                ->where(function (Builder $query): void {
                    $query->whereNull('usage_limit')
                        ->orWhereColumn('total_use', '<', 'usage_limit');
                })
                ->increment('total_use');

            if ($affected === 0 && ! $honoured()) {
                throw DiscountLimitReachedException::global($discount->code);
            }

            if ($affected === 0) {
                Discount::query()->whereKey($discount->id)->increment('total_use');
            }

            $order->promotions()->create([
                'discount_id' => $discount->id,
                'code' => $promotion->code,
                'type' => ($promotion->type ?? $discount->type)->value,
                'value_at_apply' => $promotion->value ?? $discount->value,
                'amount' => $promotion->computed_amount,
                'currency_code' => $cart->currency_code,
            ]);
        }

        ksort($campaignTotals);

        foreach ($campaignTotals as $campaignId => $spend) {
            try {
                $this->reserveCampaignBudget->execute($campaigns[$campaignId], $spend, $order->id);
            } catch (CampaignBudgetExceededException $exception) {
                if (! $honoured()) {
                    throw $exception;
                }

                $this->reserveCampaignBudget->execute($campaigns[$campaignId], $spend, $order->id, overdraw: true);
            }
        }
    }

    private function lockPromotionCounters(Cart $cart): void
    {
        if (! $this->takesForeignKeySharedLocks()) {
            return;
        }

        Discount::query()
            ->whereIn('id', CartPromotion::query()->where('cart_id', $cart->getKey())->select('discount_id'))
            ->orderBy('id')
            ->lockForUpdate()
            ->pluck('id');
    }

    private function mayAttachAutomaticPromotion(Cart $cart): bool
    {
        return Discount::query()
            ->where('trigger', PromotionSource::Automatic->value)
            ->active()
            ->whereNotIn('id', CartPromotion::query()->where('cart_id', $cart->getKey())->whereNotNull('discount_id')->select('discount_id'))
            ->exists();
    }

    private function takesForeignKeySharedLocks(): bool
    {
        return in_array((new Discount)->getConnection()->getDriverName(), ['mysql', 'mariadb'], true);
    }

    private function resolveItemName(Model $purchasable): string
    {
        if ($purchasable instanceof ProductVariant) {
            $productName = $purchasable->product?->name;

            return $productName
                ? $productName.' / '.$purchasable->name
                : $purchasable->name ?? '';
        }

        return $purchasable->name ?? '';
    }

    private function createOrderAddress(?CartAddress $cartAddress, ?int $customerId): ?OrderAddress
    {
        if (! $cartAddress) {
            return null;
        }

        return OrderAddress::query()->create([
            'customer_id' => $customerId,
            'first_name' => $cartAddress->first_name,
            'last_name' => $cartAddress->last_name,
            'company' => $cartAddress->company,
            'street_address' => $cartAddress->address_1,
            'street_address_plus' => $cartAddress->address_2,
            'city' => $cartAddress->city,
            'state' => $cartAddress->state,
            'postal_code' => $cartAddress->postal_code,
            'phone' => $cartAddress->phone,
            'country_name' => $cartAddress->country?->name,
        ]);
    }
}
