<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Database\DatabaseManager;
use Shopper\Cart\CartManager;
use Shopper\Cart\Discounts\DiscountValidator;
use Shopper\Cart\Discounts\PromotionResolver;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Models\Discount;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiValidationException;

final readonly class ApplyCartPromotionAction
{
    public function __construct(
        private DatabaseManager $database,
        private CartManager $cartManager,
        private DiscountValidator $validator,
        private PromotionResolver $resolver,
    ) {}

    /**
     * Apply a promotion code to the cart. The code is first tried in a
     * transaction that is always rolled back, under the payment session lock:
     * a code that cannot apply (unknown, currency, zone, eligibility, expiry,
     * campaign budget, minimum) leaves the cart and its payment session
     * untouched, and only an applicable code is applied for real. A valid
     * code that the resolver currently suppresses (a higher-priority exclusive
     * already won its class) is still accepted and kept on the cart. The failure
     * reason is never disclosed, to keep the codes from being enumerated.
     */
    public function execute(Cart $cart, string $code): void
    {
        $discount = Discount::query()->where('code', $code)->first();

        if (! $discount instanceof Discount || ! $discount->is_active) {
            throw ApiValidationException::withCode(ErrorCode::PromotionNotApplicable, [
                'code' => __('shopper-api::messages.promotion.not_applicable'),
            ]);
        }

        $this->cartManager->withPaymentSessionLock($cart, function () use ($cart, $code, $discount): void {
            if (! $this->applies($cart->unsetRelations()->refresh(), $code, $discount)) {
                throw ApiValidationException::withCode(ErrorCode::PromotionNotApplicable, [
                    'code' => __('shopper-api::messages.promotion.not_applicable'),
                ]);
            }

            $this->cartManager->applyCoupon($cart, $code);
        });
    }

    private function applies(Cart $cart, string $code, Discount $discount): bool
    {
        $this->database->beginTransaction();

        try {
            $cart->promotions()->firstOrCreate(
                ['discount_id' => $discount->id],
                ['source' => PromotionSource::Code->value, 'code' => $code],
            );

            $context = $this->cartManager->calculate($cart);

            return $this->validator->validate($discount, $context)->valid
                && $this->resolver->wouldApply($discount, $context);
        } finally {
            $this->database->rollBack();
            $cart->unsetRelations();
        }
    }
}
