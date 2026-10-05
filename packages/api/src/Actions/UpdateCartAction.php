<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\MissingPriceException;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Models\Zone;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiValidationException;

final readonly class UpdateCartAction
{
    public function __construct(
        private CartManager $cartManager,
    ) {}

    /**
     * Apply a partial update to the cart.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function execute(Cart $cart, array $attributes): void
    {
        $currency = $attributes['currency_code'] ?? null;

        if (is_string($currency) && $currency !== $cart->currency_code) {
            $this->cartManager->withPaymentSessionLock($cart, function () use ($cart, $currency): void {
                if ($cart->unsetRelations()->refresh()->currency_code === $currency) {
                    return;
                }

                try {
                    $this->cartManager->changeCurrency($cart, $currency);
                } catch (MissingPriceException) {
                    throw ApiValidationException::withCode(ErrorCode::PriceMissing, [
                        'currency_code' => __('shopper-api::messages.purchasable.missing_price', ['currency' => $currency]),
                    ]);
                }

                $this->cartManager->revalidateCoupons($cart);
            });
        }

        $zoneCode = $attributes['zone_code'] ?? null;
        $zoneId = is_string($zoneCode) ? (int) Zone::query()->where('code', $zoneCode)->value('id') : null;

        if ($zoneId !== null && $zoneId !== $cart->zone_id) {
            $this->cartManager->withPaymentSessionLock($cart, function () use ($cart, $zoneId): void {
                if ($cart->unsetRelations()->refresh()->zone_id === $zoneId) {
                    return;
                }

                $this->cartManager->changeContext($cart, $zoneId, $cart->channel_id);
                $this->cartManager->revalidateCoupons($cart);
            });
        }

        if (array_key_exists('email', $attributes) && is_string($attributes['email'])) {
            $this->cartManager->setEmail($cart, $attributes['email']);
        }

        if (array_key_exists('metadata', $attributes)) {
            $metadata = $attributes['metadata'];

            $this->cartManager->setMetadata($cart, is_array($metadata) ? $metadata : null);
        }
    }
}
