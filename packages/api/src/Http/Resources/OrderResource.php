<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\Order;

/**
 * @mixin Order
 */
final class OrderResource extends JsonApiResource
{
    public function toType(Request $request): string
    {
        return 'orders';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'number' => $this->number,
            'status' => $this->status->value,
            'payment_status' => $this->payment_status->value,
            'shipping_status' => $this->shipping_status->value,
            'total' => (int) $this->resource->getAttribute('items_total'),
            'tax_amount' => $this->tax_amount,
            'currency_code' => $this->currency_code,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }

    public function toRelationships(Request $request): array
    {
        return [
            'items' => fn () => OrderItemResource::collection($this->items),
        ];
    }
}
