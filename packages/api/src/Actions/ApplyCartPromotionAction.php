<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Models\Discount;

final readonly class ApplyCartPromotionAction
{
    public function __construct(
        private DatabaseManager $database,
        private CartManager $cartManager,
        private RevalidateCartCouponAction $revalidateCoupon,
    ) {}

    /**
     * Apply a promotion code to the cart. The apply-then-verify runs under a
     * row lock inside a transaction: a concurrent apply cannot interleave, and
     * a code that does not reduce the cart (unknown reason, currency, zone,
     * eligibility, expiry, or products not in the cart) rolls back so the cart
     * is left untouched. The failure reason is never disclosed to the client,
     * to keep the codes from being enumerated.
     */
    public function execute(Cart $cart, string $code): void
    {
        $discount = Discount::query()->where('code', $code)->first();

        if (! $discount instanceof Discount || ! $discount->is_active) {
            throw ValidationException::withMessages([
                'code' => __('shopper-api::messages.promotion.not_applicable'),
            ]);
        }

        $this->database->transaction(function () use ($cart, $code): void {
            $cart->newQuery()->lockForUpdate()->whereKey($cart->getKey())->first();

            $this->cartManager->applyCoupon($cart, $code);

            if (! $this->revalidateCoupon->execute($cart)) {
                throw ValidationException::withMessages([
                    'code' => __('shopper-api::messages.promotion.not_applicable'),
                ]);
            }
        });
    }
}
