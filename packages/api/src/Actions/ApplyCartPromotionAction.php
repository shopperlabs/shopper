<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Validation\ValidationException;
use Shopper\Cart\CartManager;
use Shopper\Cart\Discounts\DiscountValidator;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Models\Discount;

final readonly class ApplyCartPromotionAction
{
    public function __construct(
        private CartManager $cartManager,
        private DiscountValidator $validator,
    ) {}

    /**
     * Apply a promotion code to the cart. The code is validated against the
     * cart before it is persisted, so an invalid, expired or non applicable
     * code is rejected with its reason and leaves the cart untouched.
     */
    public function execute(Cart $cart, string $code): void
    {
        $cart->load(['lines.purchasable']);

        $discount = Discount::query()->where('code', $code)->first();

        if (! $discount instanceof Discount || ! $discount->is_active) {
            throw ValidationException::withMessages([
                'code' => __('shopper-api::messages.promotion.not_found'),
            ]);
        }

        $result = $this->validator->validate($discount, $this->cartManager->calculate($cart));

        if (! $result->valid) {
            throw ValidationException::withMessages([
                'code' => $result->failureReason ?? __('shopper-api::messages.promotion.not_applicable'),
            ]);
        }

        $this->cartManager->applyCoupon($cart, $code);

        // The code is valid in itself, but it may target products this cart
        // does not hold: confirm it actually reduces the total before keeping it.
        if ($this->cartManager->calculate($cart)->discountTotal <= 0) {
            $this->cartManager->removeCoupon($cart);

            throw ValidationException::withMessages([
                'code' => __('shopper-api::messages.promotion.not_applicable'),
            ]);
        }
    }
}
