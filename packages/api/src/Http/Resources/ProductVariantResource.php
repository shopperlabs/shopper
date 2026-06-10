<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Api\Concerns\SerializesMedia;
use Shopper\Api\Concerns\SerializesPrices;
use Shopper\Core\Models\AttributeValue;
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
            'allow_backorder' => $this->allow_backorder,
            'metadata' => $this->metadata,
            'prices' => $this->pricesPayload(),
            'values' => $this->optionValuesPayload(),
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

    /**
     * The option values this variant is built from (e.g. Color=Black, Storage=256),
     * so a storefront can match a selected option set to a variant and render swatches.
     *
     * @return array<int, array<string, mixed>>
     */
    private function optionValuesPayload(): array
    {
        if (! $this->resource->relationLoaded('values')) {
            return [];
        }

        return $this->values
            ->map(fn (AttributeValue $value): array => [
                'value' => $value->value,
                'key' => $value->key,
                'attribute' => $value->attribute->name,
                'attribute_slug' => $value->attribute->slug,
            ])
            ->values()
            ->all();
    }
}
