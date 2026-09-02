<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Cart;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Shopper\Api\Actions\CompleteCartAction;
use Shopper\Api\Concerns\RespondsWithCart;
use Shopper\Api\Concerns\RespondsWithOrder;
use Shopper\Api\Http\Resources\OrderResource;
use Symfony\Component\HttpFoundation\Response;

final class CompleteCartController
{
    use RespondsWithCart;
    use RespondsWithOrder;

    public function __construct(
        private readonly CompleteCartAction $action,
    ) {}

    /**
     * Place the order for a cart.
     *
     * The cart must carry a payment method, and a shipping method when it
     * holds shippable lines. Placement is idempotent: completing an already
     * completed cart answers 200 with the order it produced, so a client
     * retrying a timed-out call never creates a duplicate. The actual money
     * movement is confirmed through the payment webhooks: those that arrived
     * before the order existed are settled here, the rest land later.
     */
    public function __invoke(Request $request, string $cartId): JsonResponse
    {
        $cart = $this->findCart($request, $cartId);

        $order = $this->action->execute($cart);

        // The flag survives the re-fetch below and stays false on every
        // idempotent path, including the loser of a concurrent completion.
        $created = $order->wasRecentlyCreated;

        $order = $this->orderQuery()->with('items')->findOrFail($order->id);

        return OrderResource::make($order)
            ->toResponse($request)
            ->setStatusCode($created ? Response::HTTP_CREATED : Response::HTTP_OK);
    }
}
