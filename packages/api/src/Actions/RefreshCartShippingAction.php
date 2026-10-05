<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Closure;
use Shopper\Api\Support\ShippingOption;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiValidationException;

final readonly class RefreshCartShippingAction
{
    public function __construct(
        private GetCartShippingOptionsAction $shippingOptions,
        private CartManager $cartManager,
    ) {}

    /**
     * @param  Closure(): ?bool  $isCollected
     */
    public function execute(Cart $cart, Closure $isCollected): void
    {
        if (! $this->shippingOptions->requiresShipping($cart)) {
            if ($cart->shipping_option_id !== null && $isCollected() === false) {
                $this->cartManager->removeShippingMethod($cart);
            }

            return;
        }

        if (! $cart->shipping_option_id) {
            throw ApiValidationException::withCode(ErrorCode::ShippingMethodRequired, [
                'shipping_method' => __('shopper-api::messages.shipping.method_required'),
            ]);
        }

        $quotes = $cart->zone
            ? $this->shippingOptions->execute($cart)
            : ['options' => collect(), 'unavailable_carriers' => []];

        /** @var ShippingOption|null $option */
        $option = $quotes['options']->first(
            fn (ShippingOption $option): bool => $option->id() === $cart->shipping_option_id,
        );

        if (! $option && $cart->shipping_amount !== null && $isCollected() !== false) {
            return;
        }

        if (! $option) {
            $this->shippingOptions->guardCarrierOutage($cart->shipping_option_id, $quotes['unavailable_carriers']);

            throw ApiValidationException::withCode(ErrorCode::ShippingOptionUnavailable, [
                'shipping_method' => __('shopper-api::messages.shipping.option_gone'),
            ]);
        }

        $quoted = $option->rate->amount;
        $frozen = $cart->shipping_amount;

        if ($frozen !== null && $quoted !== $frozen && $isCollected() !== false) {
            return;
        }

        if ($frozen !== null && $quoted > $frozen) {
            throw ApiValidationException::withCode(ErrorCode::ShippingPriceChanged, [
                'shipping_method' => __('shopper-api::messages.shipping.price_changed'),
            ]);
        }

        if ($quoted !== $frozen) {
            $this->cartManager->setShippingMethod($cart, $option->id(), $quoted);
        }
    }
}
