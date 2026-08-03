<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\OrderShipping;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

/**
 * A shipment of the order with its carrier tracking. The tracking timeline
 * is its own relationship: a shipment summary costs nothing, the full
 * history comes with `include=shippings.events`.
 *
 * @mixin OrderShipping
 */
class OrderShippingResource extends JsonApiResource
{
    final public function toType(Request $request): string
    {
        return 'order-shippings';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'status' => $this->status?->value,
            'carrier_name' => $this->carrier?->name,
            'tracking_number' => $this->tracking_number,
            'tracking_url' => $this->tracking_url,
            'shipped_at' => $this->shipped_at->toIso8601String(),
            'received_at' => $this->received_at?->toIso8601String(),
            'returned_at' => $this->returned_at?->toIso8601String(),
        ];
    }

    public function toRelationships(Request $request): array
    {
        return [
            'events' => fn (): JsonApiResourceCollection => OrderShippingEventResource::collection($this->events),
        ];
    }
}
