<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Cart;

use Illuminate\Http\Request;
use Shopper\Api\Actions\ApplyCartPromotionAction;
use Shopper\Api\Concerns\RespondsWithCart;
use Shopper\Api\Http\Requests\Cart\ApplyPromotionRequest;
use Shopper\Cart\CartManager;
use TiMacDonald\JsonApi\JsonApiResource;

final class CartPromotionController
{
    use RespondsWithCart;

    public function __construct(
        private readonly ApplyCartPromotionAction $action,
        private readonly CartManager $cartManager,
    ) {}

    /**
     * Apply a promotion code to a cart.
     *
     * The code is validated against the cart: an unknown, inactive, expired
     * or non applicable code is rejected and the cart stays unchanged. On
     * success the discount is folded into the cart totals.
     */
    public function store(ApplyPromotionRequest $request, string $cartId): JsonApiResource
    {
        $cart = $this->findCart($request, $cartId);

        $this->mutateCart(fn () => $this->action->execute(
            cart: $cart,
            code: (string) $request->validated('code'),
        ));

        return $this->cartResource($cart->refresh());
    }

    /**
     * Remove the promotion code applied to a cart.
     */
    public function destroy(Request $request, string $cartId): JsonApiResource
    {
        $cart = $this->findCart($request, $cartId);

        $this->mutateCart(fn () => $this->cartManager->removeCoupon($cart));

        return $this->cartResource($cart->refresh());
    }
}
