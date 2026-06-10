<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Shopper\Api\Concerns\SerializesMedia;
use Shopper\Api\Concerns\SerializesPrices;
use Shopper\Core\Models\Attribute;
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
            'rating' => $this->ratingValue(),
            'reviews_count' => (int) $this->resource->getAttribute('reviews_count'),
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
            'options' => fn () => AttributeResource::collection($this->scopedOptions()),
            'relatedProducts' => fn () => ProductResource::collection($this->relatedProducts),
        ];
    }

    /**
     * The average approved rating, rounded to one decimal, or null when the
     * product has no approved reviews. Read from the withAvg aggregate alias.
     */
    private function ratingValue(): ?float
    {
        $average = $this->resource->getAttribute('average_rating');

        return $average !== null ? round((float) $average, 1) : null;
    }

    /**
     * The product's option types (Color, Storage, ...), deduplicated, with each
     * option's values narrowed to the ones this product actually uses (from the
     * options pivot). A storefront then only renders the relevant swatches and
     * builds combinations from the selected values, not every global value.
     *
     * @return Collection<int, Attribute>
     */
    private function scopedOptions(): Collection
    {
        $usedValueIds = $this->options
            ->pluck('pivot.attribute_value_id')
            ->filter()
            ->unique();

        return $this->options
            ->unique('id')
            ->values()
            ->each(fn (Attribute $option) => $option->setRelation(
                'values',
                $option->values->whereIn('id', $usedValueIds)->values(),
            ));
    }
}
