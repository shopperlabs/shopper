<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Cart;

use Illuminate\Http\Request;
use Shopper\Api\Actions\TransferCartAction;
use Shopper\Api\Concerns\RespondsWithCart;
use TiMacDonald\JsonApi\JsonApiResource;

final class CartTransferController
{
    use RespondsWithCart;

    public function __construct(
        private readonly TransferCartAction $action,
    ) {}

    /**
     * Transfer a guest cart to the authenticated customer.
     *
     * Used when a guest signs in mid checkout: the cart they built is attached
     * to their account. Idempotent for a cart they already own, refused for a
     * cart that belongs to another customer.
     */
    public function __invoke(Request $request, string $cartId): JsonApiResource
    {
        $cart = $this->findCartOrFail($cartId);

        $this->action->execute(
            cart: $cart,
            customerId: (int) $request->user()->getAuthIdentifier(),
        );

        return $this->cartResource($cart->refresh());
    }
}
