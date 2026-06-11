<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\OrderItem;

/**
 * @mixin OrderItem
 */
final class OrderItemResource extends JsonApiResource
{
    public function toType(Request $request): string
    {
        return 'order-items';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'sku' => $this->sku,
            'quantity' => $this->quantity,
            'unit_price_amount' => $this->unit_price_amount,
            'total' => $this->total,
        ];
    }
}
