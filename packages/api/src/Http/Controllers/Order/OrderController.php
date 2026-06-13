<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Order;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Shopper\Api\Concerns\RespondsWithOrder;
use Shopper\Api\Http\Resources\OrderResource;
use Shopper\Core\Models\Order;
use TiMacDonald\JsonApi\JsonApiResource;

final class OrderController
{
    use RespondsWithOrder;

    /**
     * The confirmation lookup is reachable by anyone holding the order's
     * public id, so it stays free of personal data: addresses, payment
     * method and shipments are reserved to the authenticated account
     * endpoint. Only the order lines can be expanded here.
     */
    private const ALLOWED_INCLUDES = ['items'];

    /**
     * Retrieve an order by its public id.
     *
     * This is the order confirmation lookup: a guest order is reachable by
     * anyone holding its unguessable public id, a customer order only by the
     * customer it belongs to. Expand the lines with `include=items`; the full
     * order view lives at `GET /customers/me/orders/{id}`.
     */
    public function show(Request $request, string $orderId): JsonApiResource
    {
        $this->guardIncludes($request);

        /** @var Order|null $order */
        $order = $this->orderQuery()->where('public_id', $orderId)->first();

        if (
            ! $order
            || ($order->customer_id !== null && $order->customer_id !== $request->user('sanctum')?->getAuthIdentifier())
        ) {
            throw (new ModelNotFoundException)->setModel(Order::class, [$orderId]);
        }

        return OrderResource::make($order->load('items'));
    }

    private function guardIncludes(Request $request): void
    {
        $requested = array_filter(explode(',', (string) $request->query('include')));
        $forbidden = array_diff($requested, self::ALLOWED_INCLUDES);

        if ($forbidden !== []) {
            throw ValidationException::withMessages([
                'include' => __('shopper-api::messages.order.restricted_includes'),
            ]);
        }
    }
}
