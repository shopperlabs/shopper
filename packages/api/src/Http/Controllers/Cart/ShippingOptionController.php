<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Cart;

use Illuminate\Http\Request;
use Shopper\Api\Actions\GetCartShippingOptionsAction;
use Shopper\Api\Concerns\RespondsWithCart;
use Shopper\Api\Http\Resources\ShippingOptionResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class ShippingOptionController
{
    use RespondsWithCart;

    /**
     * List the shipping options available for a cart.
     *
     * Manual zone rates are always quoted; live carrier rates require the
     * cart to carry a shipping address. The amounts are advisory: checkout
     * re-resolves the price from the option id at order placement.
     * Non-fatal issues (carrier down, foreign-currency options dropped)
     * land in `meta.warnings`.
     */
    public function __invoke(Request $request, GetCartShippingOptionsAction $action, string $cartId): JsonApiResourceCollection
    {
        $cart = $this->findCart($request, $cartId);

        $cart->load(['zone.currency', 'zone.carriers', 'lines.purchasable', 'addresses.country']);

        ['options' => $options, 'warnings' => $warnings] = $action->execute($cart);

        return ShippingOptionResource::collection($options)
            ->additional(['meta' => ['warnings' => $warnings]]);
    }
}
