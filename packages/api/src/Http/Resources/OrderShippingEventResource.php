<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\OrderShippingEvent;

/**
 * @mixin OrderShippingEvent
 */
final class OrderShippingEventResource extends JsonApiResource
{
    public function toType(Request $request): string
    {
        return 'order-shipping-events';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'status' => $this->status->value,
            'description' => $this->description,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'occurred_at' => $this->occurred_at->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
