<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Shopper\Api\Concerns\SerializesMedia;
use Shopper\Api\Concerns\SerializesPrices;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Product;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
            ...($this->isExternal() ? ['external_id' => $this->external_id] : []),
            ...$this->stockPayload(),
            ...$this->priceRangePayload(),
            'prices' => $this->canUseVariants() ? [] : $this->pricesPayload(),
            'images' => $this->imagesPayload(),
            'thumbnail' => $this->thumbnailPayload(withFallback: true),
            ...($this->isVirtual() ? ['files' => $this->filesPayload()] : []),
            ...$this->ratingPayload(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    public function toRelationships(Request $request): array
    {
        $relationships = [
            'brand' => fn () => BrandResource::make($this->brand),
            'categories' => fn () => CategoryResource::collection($this->categories),
            'collections' => fn () => CollectionResource::collection($this->collections),
            'tags' => fn () => TagResource::collection($this->tags),
            'relatedProducts' => fn () => ProductResource::collection($this->relatedProducts),
        ];

        if ($this->canUseVariants()) {
            $relationships['variants'] = fn () => ProductVariantResource::collection($this->variants);
        }

        if ($this->canUseAttributes()) {
            $relationships['options'] = fn () => AttributeResource::collection($this->scopedOptions());
        }

        return $relationships;
    }

    /**
     * @return array<string, int|bool>
     */
    private function stockPayload(): array
    {
        if ($this->isExternal()) {
            return ['in_stock' => true];
        }

        $raw = $this->resource->getAttributes();

        if ($this->canUseVariants()) {
            if (! array_key_exists('variants_real_stock', $raw)) {
                return [];
            }

            return [
                'in_stock' => (int) $raw['variants_real_stock'] > 0 || ($raw['variants_allow_backorder'] ?? false),
            ];
        }

        if (! array_key_exists('real_stock', $raw)) {
            return [];
        }

        $stock = $this->stock;

        return [
            'stock' => $stock,
            'in_stock' => $stock > 0 || $this->allow_backorder,
        ];
    }

    /**
     * Review aggregates only when the client opted in through
     * `include=rating` (RatingAggregate), never by default. The average is
     * rounded to one decimal and null without approved reviews.
     *
     * @return array<string, int|float|null>
     */
    private function ratingPayload(): array
    {
        $raw = $this->resource->getAttributes();

        if (! array_key_exists('reviews_count', $raw)) {
            return [];
        }

        $average = $raw['average_rating'] ?? null;

        return [
            'rating' => $average !== null ? round((float) $average, 1) : null,
            'reviews_count' => (int) $raw['reviews_count'],
        ];
    }

    /**
     * The min/max price aggregate in the currency resolved for the request,
     * batch-loaded by the controller (LoadsPriceRange). Null when the product
     * has no price in that currency; absent on responses that do not load it.
     *
     * @return array<string, array<string, int|string>|null>
     */
    private function priceRangePayload(): array
    {
        $raw = $this->resource->getAttributes();

        if (! array_key_exists('price_range_min', $raw)) {
            return [];
        }

        $currency = request()->attributes->get('shopper_price_currency');

        return [
            'price_range' => $raw['price_range_min'] === null || $currency === null ? null : [
                'currency_code' => $currency->code,
                'min' => (int) $raw['price_range_min'],
                'max' => (int) $raw['price_range_max'],
            ],
        ];
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

        $swatches = $this->swatchUrlMap();

        return $this->options
            ->unique('id')
            ->values()
            ->each(fn (Attribute $option) => $option->setRelation(
                'values',
                $option->values
                    ->whereIn('id', $usedValueIds)
                    ->each(function (AttributeValue $value) use ($swatches): void {
                        $value->swatch_url = $swatches[$value->id] ?? null;
                    })
                    ->values(),
            ));
    }

    /**
     * Per-product swatch image URL for each attribute value, read from the
     * product's `attribute_product` rows. Empty unless `attributeProducts.media`
     * was eager-loaded (the `options` include), keeping listings N+1 free.
     *
     * @return array<int, string>
     */
    private function swatchUrlMap(): array
    {
        if (! $this->resource->relationLoaded('attributeProducts')) {
            return [];
        }

        return $this->attributeProducts
            ->filter(fn (AttributeProduct $attributeProduct): bool => $attributeProduct->attribute_value_id !== null)
            ->mapWithKeys(function (AttributeProduct $attributeProduct): array {
                $url = '';

                if ($attributeProduct instanceof SpatieHasMedia) {
                    $media = $attributeProduct->getMedia('swatch')->first();
                    $url = $media instanceof Media ? $media->getFullUrl() : '';
                }

                return [$attributeProduct->attribute_value_id => $url];
            })
            ->filter(fn (string $url): bool => $url !== '')
            ->all();
    }
}
