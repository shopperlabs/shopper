<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Shopper\Api\Http\Resources\CartResource;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Models\Contracts\Cart as CartContract;

trait RespondsWithCart
{
    protected function findCart(Request $request, string $publicId): Cart
    {
        /** @var Cart|null $cart */
        $cart = resolve(CartContract::class)::query()->wherePublicId($publicId)->first();

        if (
            ! $cart
            || ($cart->customer_id !== null && $cart->customer_id !== $request->user('sanctum')?->getAuthIdentifier())
        ) {
            throw (new ModelNotFoundException)->setModel(Cart::class, [$publicId]);
        }

        return $cart;
    }

    /**
     * Every cart response carries the totals computed by the cart pipelines,
     * so a single GET is enough to render the cart. Relations are reloaded
     * after the run because the pipelines rewrite adjustments and tax lines;
     * whether they are serialized is up to the client through `include`.
     */
    protected function cartResource(Cart $cart): CartResource
    {
        $context = resolve(CartManager::class)->calculate($cart);

        $cart->load(['lines.purchasable', 'lines.adjustments', 'lines.taxLines', 'addresses.country']);

        return CartResource::make($cart)->withTotals($context);
    }
}
