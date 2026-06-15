<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;

final readonly class RevalidateCartCouponAction
{
    public function __construct(
        private CartManager $cartManager,
    ) {}

    /**
     * Drop the cart coupon when it no longer reduces the cart. The discount
     * pipeline re-validates the code on every calculation (currency, zone,
     * eligibility, expiry, per-customer limit), so a zero discount means the
     * coupon stopped applying after a context change (currency switch, cart
     * transfer to a customer who already redeemed it, ...). Keeping a coupon
     * that contributes nothing would advertise a stale code to the storefront.
     *
     * Returns whether a coupon is still applied.
     */
    public function execute(Cart $cart): bool
    {
        if (! $cart->promotions()->exists()) {
            return false;
        }

        if ($this->cartManager->calculate($cart)->discountTotal <= 0) {
            $this->cartManager->removeCoupon($cart);

            return false;
        }

        return true;
    }
}
