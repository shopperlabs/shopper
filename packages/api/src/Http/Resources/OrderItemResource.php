<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Api\Concerns\SerializesMedia;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\OrderItem;

/**
 * @mixin OrderItem
 */
class OrderItemResource extends JsonApiResource
{
    use SerializesMedia;

    final public function toType(Request $request): string
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
            'metadata' => $this->metadata,
            ...($this->hasDownloadableFiles() ? ['files' => $this->filesPayload($this->product)] : []),
        ];
    }

    private function hasDownloadableFiles(): bool
    {
        if (! $this->resource->relationLoaded('product')) {
            return false;
        }

        $product = $this->product;

        return $product instanceof Product && $product->isVirtual();
    }
}
