<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Api\Concerns\SerializesMedia;
use Shopper\Api\Concerns\SerializesPrices;
use Shopper\Core\Models\ProductVariant;

/**
 * @mixin ProductVariant
 */
final class ProductVariantResource extends JsonApiResource
{
    use SerializesMedia;
    use SerializesPrices;

    public function toType(Request $request): string
    {
        return 'variants';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'ean' => $this->ean,
            'upc' => $this->upc,
            'position' => $this->position,
            'metadata' => $this->metadata,
            'prices' => $this->pricesPayload(),
            'images' => $this->imagesPayload(),
            'thumbnail' => $this->thumbnailPayload(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    public function toRelationships(Request $request): array
    {
        return [
            'product' => fn () => ProductResource::make($this->product),
        ];
    }
}
