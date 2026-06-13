<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Cart;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Shopper\Api\Concerns\RespondsWithCart;
use Shopper\Api\Http\Requests\Cart\CreateCartRequest;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\Contracts\Cart as CartContract;
use Shopper\Core\Models\Zone;
use Symfony\Component\HttpFoundation\Response;
use TiMacDonald\JsonApi\JsonApiResource;

final class CartController
{
    use RespondsWithCart;

    /**
     * Create a cart.
     *
     * Starts a guest cart, or a customer cart when the request carries a
     * Sanctum token. The currency falls back to the resolved zone, then to
     * the shop default. Persist the returned id on the client: it is the only
     * way back to a guest cart.
     */
    public function store(CreateCartRequest $request): JsonResponse
    {
        /** @var Zone|null $zone */
        $zone = $request->attributes->get('shopper_zone');

        /** @var Cart $cart */
        $cart = resolve(CartContract::class)::query()->create([
            'currency_code' => $request->validated('currency_code')
                ?? ($zone?->currency_id ? $zone->currency_code : shopper_currency()),
            'email' => $request->validated('email'),
            'customer_id' => $request->user('sanctum')?->getAuthIdentifier(),
            'zone_id' => $zone?->id,
            'metadata' => $request->validated('metadata'),
        ]);

        $response = $this->cartResource($cart)->toResponse($request);

        return $response->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Retrieve a cart.
     *
     * Returns the cart summary with its pipeline-computed totals. Expand the
     * heavy parts on demand: `include=lines`, `include=lines.purchasable`,
     * `include=addresses`.
     */
    public function show(Request $request, string $cartId): JsonApiResource
    {
        return $this->cartResource($this->findCart($request, $cartId));
    }
}
