<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Validation\ValidationException;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Models\Price;

final readonly class UpdateCartAction
{
    public function __construct(
        private CartManager $cartManager,
        private CancelPaymentSessionAction $cancelSession,
        private RevalidateCartCouponAction $revalidateCoupon,
    ) {}

    /**
     * Apply a partial update to the cart. Only the keys present in the payload
     * are touched. Switching the currency re-prices the lines, so it is
     * rejected when a line has no price in the target currency; the open
     * payment intent is cancelled and an applied coupon is dropped when it no
     * longer reduces the re-priced cart.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function execute(Cart $cart, array $attributes): void
    {
        $currency = $attributes['currency_code'] ?? null;

        if (is_string($currency) && $currency !== $cart->currency_code) {
            $this->guardLinePrices($cart, $currency);

            $session = $cart->payment_session;

            $this->cartManager->changeCurrency($cart, $currency);
            $this->cancelSession->execute($session);
            $this->revalidateCoupon->execute($cart);
        }

        if (array_key_exists('email', $attributes) && is_string($attributes['email'])) {
            $this->cartManager->setEmail($cart, $attributes['email']);
        }

        if (array_key_exists('metadata', $attributes)) {
            $metadata = $attributes['metadata'];

            $this->cartManager->setMetadata($cart, is_array($metadata) ? $metadata : null);
        }
    }

    private function guardLinePrices(Cart $cart, string $currencyCode): void
    {
        $cart->loadMissing('lines.purchasable.prices');

        foreach ($cart->lines as $line) {
            $purchasable = $line->purchasable;

            if (! $purchasable instanceof Priceable || ! $purchasable->getPrice($currencyCode) instanceof Price) {
                throw ValidationException::withMessages([
                    'currency_code' => __('shopper-api::messages.purchasable.missing_price', ['currency' => $currencyCode]),
                ]);
            }
        }
    }
}
