<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Api\Concerns\SerializesMedia;
use Shopper\Api\Concerns\SerializesPrices;
use Shopper\Core\Models\Product;

/**
 * @mixin Product
 */
final class ProductResource extends JsonApiResource
{
    use SerializesMedia;
    use SerializesPrices;

    public function toType(Request $request): string
    {
        return 'products';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'summary' => $this->summary,
            'description' => $this->description,
            'featured' => $this->featured,
            'type' => $this->type?->value,
            'published_at' => $this->published_at?->toIso8601String(),
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'metadata' => $this->metadata,
            'prices' => $this->pricesPayload(),
            'images' => $this->imagesPayload(),
            'thumbnail' => $this->thumbnailPayload(),
            'files' => $this->filesPayload(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    public function toRelationships(Request $request): array
    {
        return [
            'brand' => fn () => BrandResource::make($this->brand),
            'variants' => fn () => ProductVariantResource::collection($this->variants),
            'categories' => fn () => CategoryResource::collection($this->categories),
            'collections' => fn () => CollectionResource::collection($this->collections),
            'options' => fn () => AttributeResource::collection($this->options),
        ];
    }
}
