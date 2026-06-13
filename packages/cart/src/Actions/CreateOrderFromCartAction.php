<?php

declare(strict_types=1);

namespace Shopper\Cart\Actions;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Shopper\Cart\CartManager;
use Shopper\Cart\Events\CartCompleted;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\DiscountLimitReachedException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartAddress;
use Shopper\Cart\Models\Contracts\Cart as CartContract;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Actions\CreateOrderTaxLinesAction;
use Shopper\Core\Actions\ReserveCampaignBudget;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\OrderAddress;
use Shopper\Core\Models\ProductVariant as ProductVariantModel;
use Throwable;

final readonly class CreateOrderFromCartAction
{
    public function __construct(
        private CartManager $cartManager,
        private CreateOrderTaxLinesAction $createOrderTaxLines,
        private ReserveCampaignBudget $reserveCampaignBudget,
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

            $discount = $this->reserveDiscount($cart, $context);

            $shippingAddress = $this->createOrderAddress($cart->shippingAddress(), $cart->customer_id);
            $billingAddress = $this->createOrderAddress($cart->billingAddress(), $cart->customer_id);

            $order = resolve(Order::class)::query()->create([
                'number' => generate_number(),
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
                'discount_id' => $discount?->id,
                'discount_code' => $discount?->code,
                'discount_type' => $discount?->type->value,
                'discount_value_at_apply' => $discount?->value,
                'discount_currency_code' => $discount !== null ? $cart->currency_code : null,
            ]);

            $cart->lines->loadMorph('purchasable', [
                ProductVariantModel::class => ['product'],
            ]);

            foreach ($cart->lines as $line) {
                $discountAmount = $line->adjustments->sum('amount');
                $purchasable = $line->purchasable;

                $order->items()->create([
                    'name' => $this->resolveItemName($purchasable),
                    'sku' => $purchasable->sku ?? '',
                    'quantity' => $line->quantity,
                    'unit_price_amount' => $line->unit_price_amount,
                    'discount_amount' => $discountAmount,
                    'product_type' => $line->purchasable_type,
                    'product_id' => $line->purchasable_id,
                ]);
            }

            $this->createOrderTaxLines->execute($order);

            $this->reserveCampaignBudgetFor($discount, $context, $order->id);

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
     * Atomically reserve a usage slot for the cart's discount, if any.
     * Only reserves when the discount actually reduced the cart, so a stale
     * or inapplicable coupon never burns a usage slot. Throws if the global
     * limit was exhausted between validation and commit, or if the discount
     * is restricted to one use per customer and this customer already redeemed it.
     */
    private function reserveDiscount(Cart $cart, CartPipelineContext $context): ?Discount
    {
        if (! $cart->coupon_code || $context->discountTotal <= 0) {
            return null;
        }

        $discount = Discount::query()
            ->where('code', $cart->coupon_code)
            ->lockForUpdate()
            ->first();

        if ($discount === null) {
            return null;
        }

        if ($discount->usage_limit_per_user && $cart->customer_id !== null) {
            $alreadyRedeemed = resolve(Order::class)::query()
                ->where('discount_id', $discount->id)
                ->where('customer_id', $cart->customer_id)
                ->exists();

            if ($alreadyRedeemed) {
                throw DiscountLimitReachedException::perUser($discount->code);
            }
        }

        $affected = Discount::query()
            ->whereKey($discount->id)
            ->where(function ($query): void {
                $query->whereNull('usage_limit')
                    ->orWhereColumn('total_use', '<', 'usage_limit');
            })
            ->increment('total_use');

        if ($affected === 0) {
            throw DiscountLimitReachedException::global($discount->code);
        }

        $discount->refresh();

        return $discount;
    }

    /**
     * Draw down the parent campaign budget once the order exists, so the
     * movement is keyed to the order and a retried checkout cannot double
     * spend (the cart lock and completed_at guard above already wrap this).
     * Campaigns without caps still record the movement for analytics.
     */
    private function reserveCampaignBudgetFor(?Discount $discount, CartPipelineContext $context, int $orderId): void
    {
        if ($discount === null || $discount->campaign_id === null) {
            return;
        }

        $campaign = $discount->campaign;

        if ($campaign === null) {
            return;
        }

        $this->reserveCampaignBudget->execute($campaign, $context->discountTotal, $orderId);
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
