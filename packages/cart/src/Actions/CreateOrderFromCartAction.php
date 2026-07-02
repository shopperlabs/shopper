<?php

declare(strict_types=1);

namespace Shopper\Cart\Actions;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Shopper\Cart\CartManager;
use Shopper\Cart\Events\CartCompleted;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\DiscountLimitReachedException;
use Shopper\Cart\Exceptions\InsufficientStockException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartAddress;
use Shopper\Cart\Models\CartPromotion;
use Shopper\Cart\Models\Contracts\Cart as CartContract;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Actions\ReserveCampaignBudget;
use Shopper\Core\Contracts\StockReserver;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\Contracts\Stockable;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\OrderAddress;
use Shopper\Core\Models\OrderPromotion;
use Shopper\Core\Models\OrderTaxLine;
use Shopper\Core\Models\ProductVariant as ProductVariantModel;
use Throwable;

final readonly class CreateOrderFromCartAction
{
    public function __construct(
        private CartManager $cartManager,
        private ReserveCampaignBudget $reserveCampaignBudget,
        private StockReserver $stockReserver,
    ) {}

    /**
     * @param  Closure(CartPipelineContext): void|null  $assertTotals  Runs against
     *                                                                 the totals computed under the cart lock, right before the order
     *                                                                 freezes them. Throw to abort: the transaction rolls back.
     *
     * @throws Throwable
     */
    public function execute(Cart $cart, ?Closure $assertTotals = null): Order
    {
        return DB::transaction(function () use ($cart, $assertTotals): Order {
            /** @var Cart $cart */
            $cart = resolve(CartContract::class)::query()->lockForUpdate()->findOrFail($cart->id);

            if ($cart->isCompleted()) {
                throw new CartCompletedException;
            }

            $context = $this->cartManager->calculate($cart);

            if ($assertTotals) {
                $assertTotals($context);
            }

            $applied = $cart->promotions
                ->where('computed_amount', '>', 0)
                ->sortBy('sequence')
                ->values();

            $primary = $applied->sortByDesc('computed_amount')->first();
            $primaryDiscount = $primary?->discount;

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
                'discount_type' => $primaryDiscount?->type->value,
                'discount_value_at_apply' => $primaryDiscount?->value,
                'discount_currency_code' => $applied->isNotEmpty() ? $cart->currency_code : null,
            ]);

            $cart->lines->loadMorph('purchasable', [
                ProductVariantModel::class => ['product'],
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

            $this->reservePromotions($applied, $cart, $order);

            $order->refresh();

            $cart->update([
                'completed_at' => now(),
                'order_id' => $order->id,
            ]);

            CartCompleted::dispatch($cart, $order);

            return $order;
        });
    }

    /**
     * A flat-rate option id is `{carrier_code}:{carrier_option_public_id}`,
     * so the carrier option row can be linked back on the order. Live carrier
     * rates have no local row and leave the foreign key empty; the frozen
     * shipping_amount remains the source of truth for what was charged.
     */
    private function resolveCarrierOptionId(?string $shippingOptionId): ?int
    {
        if (! $shippingOptionId || ! str_contains($shippingOptionId, ':')) {
            return null;
        }

        [, $serviceCode] = explode(':', $shippingOptionId, 2);

        return CarrierOption::query()->where('public_id', $serviceCode)->value('id');
    }

    /**
     * Reserve and snapshot every applied promotion once the order exists: bump
     * each code's usage counter atomically (throwing on a limit lost to a race
     * or a per-customer reuse), record a per-promotion snapshot on the order, and
     * draw down each parent campaign budget once per campaign (summed across its
     * promotions). All keyed to the order, so a retried checkout cannot double
     * reserve.
     *
     * @param  Collection<int, CartPromotion>  $applied
     */
    private function reservePromotions(Collection $applied, Cart $cart, Order $order): void
    {
        $campaignTotals = [];
        $campaigns = [];

        foreach ($applied as $promotion) {
            // No lockForUpdate: the conditional increment below is atomic on its
            // own, the per-user check is a soft guard, and type/value/code are
            // frozen config. Locking the row here would only add contention.
            $discount = Discount::query()
                ->whereKey($promotion->discount_id)
                ->first();

            // Deactivated between the locked recalculation and here: do not charge it.
            if ($discount === null || ! $discount->is_active) {
                continue;
            }

            if ($discount->usage_limit_per_user) {
                $column = $cart->customer_id !== null ? 'customer_id' : 'email';
                $value = $cart->customer_id ?? $cart->email;

                $alreadyRedeemed = $value !== null && OrderPromotion::query()
                    ->where('discount_id', $discount->id)
                    ->whereHas('order', fn (Builder $query) => $query->where($column, $value))
                    ->exists();

                if ($alreadyRedeemed) {
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

            if ($affected === 0) {
                throw DiscountLimitReachedException::global($discount->code);
            }

            $order->promotions()->create([
                'discount_id' => $discount->id,
                'code' => $promotion->code,
                'type' => $discount->type->value,
                'value_at_apply' => $discount->value,
                'amount' => $promotion->computed_amount,
                'currency_code' => $cart->currency_code,
            ]);

            if ($discount->campaign_id !== null && $discount->campaign !== null) {
                $campaignTotals[$discount->campaign_id] = ($campaignTotals[$discount->campaign_id] ?? 0) + $promotion->computed_amount;
                $campaigns[$discount->campaign_id] = $discount->campaign;
            }
        }

        foreach ($campaignTotals as $campaignId => $spend) {
            $this->reserveCampaignBudget->execute($campaigns[$campaignId], $spend, $order->id);
        }
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
