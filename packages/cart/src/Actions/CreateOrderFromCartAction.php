<?php

declare(strict_types=1);

namespace Shopper\Cart\Actions;

use Illuminate\Support\Facades\DB;
use Shopper\Cart\CartManager;
use Shopper\Cart\Events\CartCompleted;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartAddress;
use Shopper\Core\Actions\CreateOrderTaxLinesAction;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderAddress;

final class CreateOrderFromCartAction
{
    public function __construct(
        private readonly CartManager $cartManager,
        private readonly CreateOrderTaxLinesAction $createOrderTaxLines,
    ) {}

    public function execute(Cart $cart): Order
    {
        return DB::transaction(function () use ($cart): Order {
            /** @var Cart $cart */
            $cart = Cart::query()->lockForUpdate()->findOrFail($cart->id);

            if ($cart->isCompleted()) {
                throw new CartCompletedException;
            }

            $context = $this->cartManager->calculate($cart);

            $shippingAddress = $this->createOrderAddress($cart->shippingAddress());
            $billingAddress = $this->createOrderAddress($cart->billingAddress());

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
            ]);

            foreach ($cart->lines as $line) {
                $discountAmount = $line->adjustments->sum('amount');

                $order->items()->create([
                    'name' => $line->purchasable->name ?? '',
                    'sku' => $line->purchasable->sku ?? '',
                    'quantity' => $line->quantity,
                    'unit_price_amount' => $line->unit_price_amount,
                    'discount_amount' => $discountAmount,
                    'product_type' => $line->purchasable_type,
                    'product_id' => $line->purchasable_id,
                ]);
            }

            $this->createOrderTaxLines->execute($order);

            $order->refresh();

            if ($cart->coupon_code) {
                Discount::query()
                    ->where('code', $cart->coupon_code)
                    ->where(function ($query): void {
                        $query->whereNull('usage_limit')
                            ->orWhereColumn('total_use', '<', 'usage_limit');
                    })
                    ->increment('total_use');
            }

            $cart->update(['completed_at' => now()]);

            CartCompleted::dispatch($cart, $order);

            return $order;
        });
    }

    private function createOrderAddress(?CartAddress $cartAddress): ?OrderAddress
    {
        if (! $cartAddress) {
            return null;
        }

        return OrderAddress::query()->create([
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
