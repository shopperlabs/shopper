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
class ProductVariantResource extends JsonApiResource
{
    use SerializesMedia;
    use SerializesPrices;

    final public function toType(Request $request): string
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
            ...$this->stockPayload(),
            'metadata' => $this->metadata,
            'prices' => $this->pricesPayload(),
            'values' => $this->optionValuesPayload(),
            'images' => $this->imagesPayload(),
            'thumbnail' => $this->thumbnailPayload(withFallback: true),
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
     * Stock fields only when batch-loaded by the controller (LoadsStock):
     * serializing them lazily would run one stock sum query per variant.
     *
     * @return array<string, int|bool>
     */
    private function stockPayload(): array
    {
        if (! array_key_exists('real_stock', $this->resource->getAttributes())) {
            return [];
        }

        $stock = $this->stock;

        return [
            'stock' => $stock,
            'in_stock' => $stock > 0 || $this->allow_backorder,
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
