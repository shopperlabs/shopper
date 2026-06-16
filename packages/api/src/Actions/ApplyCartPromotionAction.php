<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Shopper\Cart\CartManager;
use Shopper\Cart\Discounts\DiscountValidator;
use Shopper\Cart\Discounts\PromotionResolver;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Models\Discount;

final readonly class ApplyCartPromotionAction
{
    public function __construct(
        private DatabaseManager $database,
        private CartManager $cartManager,
        private DiscountValidator $validator,
        private PromotionResolver $resolver,
    ) {}

    /**
     * Apply a promotion code to the cart. The apply-then-verify runs under a
     * row lock inside a transaction: a concurrent apply cannot interleave, and a
     * code that cannot apply (unknown, currency, zone, eligibility, expiry,
     * campaign budget, minimum) rolls back so the cart is left untouched. A valid
     * code that the resolver currently suppresses (a higher-priority exclusive
     * already won its class) is still accepted and kept on the cart. The failure
     * reason is never disclosed, to keep the codes from being enumerated.
     */
    public function execute(Cart $cart, string $code): void
    {
        $discount = Discount::query()->where('code', $code)->first();

        if (! $discount instanceof Discount || ! $discount->is_active) {
            throw ValidationException::withMessages([
                'code' => __('shopper-api::messages.promotion.not_applicable'),
            ]);
        }

        $this->database->transaction(function () use ($cart, $code, $discount): void {
            $cart->newQuery()->lockForUpdate()->whereKey($cart->getKey())->first();

            $this->cartManager->applyCoupon($cart, $code);

            $context = $this->cartManager->calculate($cart);

            $applies = $this->validator->validate($discount, $context)->valid
                && $this->resolver->wouldApply($discount, $context);

            if (! $applies) {
                $this->cartManager->removeCoupon($cart, $code);

                throw ValidationException::withMessages([
                    'code' => __('shopper-api::messages.promotion.not_applicable'),
                ]);
            }
        });
    }
}
