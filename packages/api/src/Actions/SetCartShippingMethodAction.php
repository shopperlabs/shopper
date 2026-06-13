<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Validation\ValidationException;
use Shopper\Api\Support\ShippingOption;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;

final readonly class SetCartShippingMethodAction
{
    public function __construct(
        private GetCartShippingOptionsAction $shippingOptions,
        private CartManager $cartManager,
    ) {}

    /**
     * Bind a delivery choice to the cart from its composite option id. The
     * option is re-quoted server-side so the stored amount is always a price
     * the carrier actually offered, never a value the client sent.
     */
    public function execute(Cart $cart, string $optionId): void
    {
        $cart->load(['zone.currency', 'zone.carriers', 'lines.purchasable', 'addresses.country']);

        ['options' => $options] = $this->shippingOptions->execute($cart);

        /** @var ShippingOption|null $option */
        $option = $options->first(
            fn (ShippingOption $option): bool => $option->id() === $optionId,
        );

        if (! $option) {
            throw ValidationException::withMessages([
                'option_id' => __('shopper-api::messages.shipping.option_not_available'),
            ]);
        }

        $this->cartManager->setShippingMethod($cart, $option->id(), $option->rate->amount);
    }
}
