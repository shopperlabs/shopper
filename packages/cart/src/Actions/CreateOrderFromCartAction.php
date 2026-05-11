<?php

declare(strict_types=1);

namespace Shopper\Cart\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Shopper\Cart\CartManager;
use Shopper\Cart\Events\CartCompleted;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\DiscountLimitReachedException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartAddress;
use Shopper\Core\Actions\CreateOrderTaxLinesAction;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderAddress;
use Shopper\Core\Models\ProductVariant as ProductVariantModel;

final readonly class CreateOrderFromCartAction
{
    public function __construct(
        private CartManager $cartManager,
        private CreateOrderTaxLinesAction $createOrderTaxLines,
    ) {}

    public function execute(Cart $cart): Order
    {
        return DB::transaction(function () use ($cart): Order {
            /** @var Cart $cart */
            $cart = Cart::query()->lockForUpdate()->findOrFail($cart->id);

            if ($cart->isCompleted()) {
                throw new CartCompletedException;
            }

            $discount = $this->reserveDiscount($cart);

            $context = $this->cartManager->calculate($cart);

            $shippingAddress = $this->createOrderAddress($cart->shippingAddress(), $cart->customer_id);
            $billingAddress = $this->createOrderAddress($cart->billingAddress(), $cart->customer_id);

            $order = Order::query()->create([
                'number' => generate_number(),
                'price_amount' => $context->total,
                'tax_amount' => $context->taxTotal,
                'currency_code' => $cart->currency_code,
                'customer_id' => $cart->customer_id,
                'channel_id' => $cart->channel_id,
                'zone_id' => $cart->zone_id,
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

            $order->refresh();

            $cart->update(['completed_at' => now()]);

            CartCompleted::dispatch($cart, $order);

            return $order;
        });
    }

    /**
     * Atomically reserve a usage slot for the cart's discount, if any.
     * Throws if the global limit was exhausted between validation and commit,
     * or if the discount is restricted to one use per customer and this
     * customer has already redeemed it.
     */
    private function reserveDiscount(Cart $cart): ?Discount
    {
        if (! $cart->coupon_code) {
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
            $alreadyRedeemed = Order::query()
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
