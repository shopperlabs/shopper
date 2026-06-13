<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Account;

use Illuminate\Http\Request;
use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Concerns\RespondsWithOrder;
use Shopper\Api\Http\Resources\OrderResource;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class OrderController
{
    use BuildsApiQueries;
    use RespondsWithOrder;

    /**
     * List the customer's orders.
     *
     * A summary per order: number, statuses, totals. The detail endpoint is
     * where the addresses, payment method and shipments live.
     */
    public function index(Request $request): JsonApiResourceCollection
    {
        $query = $this->orderQuery()
            ->where('customer_id', $request->user()?->getAuthIdentifier());

        return OrderResource::collection(
            $this->paginated('order', $query, defaultSort: '-created_at')
        );
    }

    /**
     * Retrieve one of the customer's orders.
     *
     * The full order view: expand what the account page needs through
     * `include=items,shipping_address,billing_address,payment_method,shippings,shippings.events,refund`.
     * Orders of other customers answer 404, indistinguishable from an order
     * that does not exist.
     */
    public function show(Request $request, string $orderId): JsonApiResource
    {
        $order = $this->orderQuery()
            ->where('customer_id', $request->user()?->getAuthIdentifier())
            ->where('public_id', $orderId)
            ->firstOrFail();

        $order->load([
            'items',
            'shippingAddress',
            'billingAddress',
            'paymentMethod',
            'shippings.carrier',
            'shippings.events',
            'refund',
        ]);

        return OrderResource::make($order);
    }
}
