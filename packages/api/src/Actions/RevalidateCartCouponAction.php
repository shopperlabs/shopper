<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Shopper\Cart\CartManager;
use Shopper\Cart\Discounts\DiscountValidator;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Models\Discount;

final readonly class RevalidateCartCouponAction
{
    public function __construct(
        private CartManager $cartManager,
        private DiscountValidator $validator,
    ) {}

    /**
     * Drop the promotions that stopped applying after a context change (currency
     * switch, cart transfer to a customer who already redeemed a code, ...). Each
     * promotion is revalidated independently and only the genuinely invalid ones
     * are removed; a valid promotion the resolver merely suppresses is kept.
     *
     * Returns whether any promotion is still applied.
     */
    public function execute(Cart $cart): bool
    {
        $promotions = $cart->promotions()->with('discount')->get();

        if ($promotions->isEmpty()) {
            return false;
        }

        $context = $this->cartManager->calculate($cart);

        foreach ($promotions as $promotion) {
            $discount = $promotion->discount;

            if ($discount instanceof Discount
                && $discount->is_active
                && $promotion->code !== null
                && $this->validator->validate($discount, $context)->valid) {
                continue;
            }

            if ($promotion->code !== null) {
                $this->cartManager->removeCoupon($cart, $promotion->code);
            }
        }

        return $cart->promotions()->exists();
    }
}
