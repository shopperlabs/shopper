<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Cart;

use Shopper\Api\Actions\SetCartShippingMethodAction;
use Shopper\Api\Concerns\RespondsWithCart;
use Shopper\Api\Http\Requests\Cart\SetShippingMethodRequest;
use TiMacDonald\JsonApi\JsonApiResource;

final class CartShippingMethodController
{
    use RespondsWithCart;

    public function __construct(
        private readonly SetCartShippingMethodAction $action,
    ) {}

    /**
     * Set the shipping method of a cart.
     *
     * Send back the composite `option_id` quoted by the shipping options
     * endpoint. The price is re-resolved server-side and folded into the
     * cart totals; an option the carriers no longer quote is rejected.
     */
    public function store(SetShippingMethodRequest $request, string $cartId): JsonApiResource
    {
        $cart = $this->findCart($request, $cartId);

        $this->mutateCart(fn () => $this->action->execute(
            cart: $cart,
            optionId: (string) $request->validated('option_id'),
        ));

        return $this->cartResource($cart->refresh());
    }
}
