<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Api\Concerns\SerializesMedia;
use Shopper\Api\Concerns\SerializesPrices;
use Shopper\Api\Http\Includes\EnabledRelation;
use Shopper\Api\Http\Includes\ListingPriceRange;
use Shopper\Api\Http\Includes\PublicProducts;
use Shopper\Api\Http\Includes\PublishedRelation;
use Shopper\Api\Http\Includes\RatingAggregate;
use Shopper\Api\Http\Includes\ScopedOptions;
use Shopper\Api\Http\Includes\VisibleCategories;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\Product;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

/**
 * @mixin Product
 */
class ProductResource extends JsonApiResource
{
    use SerializesMedia;
    use SerializesPrices;

    public static function filters(): array
    {
        return [
            AllowedFilter::partial('name'),
            AllowedFilter::exact('sku'),
            AllowedFilter::scope('q', 'matching'),
            AllowedFilter::exact('featured'),
            AllowedFilter::scope('in_stock', 'availability'),
            AllowedFilter::scope('category'),
            AllowedFilter::scope('category_tree', 'categoryTree'),
            AllowedFilter::scope('collection'),
            AllowedFilter::scope('brand', 'byBrand'),
            AllowedFilter::scope('tag'),
            AllowedFilter::scope('option'),
        ];
    }

    public static function sorts(): array
    {
        return [
            'name',
            'created_at',
            'published_at',
            AllowedSort::field('price', 'min_price'),
        ];
    }

    public static function includes(): array
    {
        return [
            AllowedInclude::custom('brand', new EnabledRelation),
            'variants',
            AllowedInclude::custom('categories', new VisibleCategories),
            AllowedInclude::custom('collections', new PublishedRelation),
            AllowedInclude::custom('options', new ScopedOptions),
            'tags',
            AllowedInclude::custom('relatedProducts', new PublicProducts),
            AllowedInclude::custom('rating', new RatingAggregate),
            AllowedInclude::custom('price_range', new ListingPriceRange),
        ];
    }

    public static function includeLoads(): array
    {
        return [
            'variants' => ['variants.prices.currency', 'variants.values.attribute'],
            'categories' => ['categories.parent'],
        ];
    }

    public static function eagerLoads(): array
    {
        return [
            'prices.currency',
            ...(method_exists(resolve(ProductContract::class), 'getMedia') ? ['media'] : []),
        ];
    }

    final public function toType(Request $request): string
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
            ...($this->canUseVariants() ? [] : $this->calculatedPricePayload()),
            'images' => $this->imagesPayload(),
            'thumbnail' => $this->thumbnailPayload(withFallback: true),
            ...($this->isVirtual() ? ['files' => $this->filesPayload()] : []),
            ...$this->ratingPayload(),
            ...$this->optionsPayload(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    public function toRelationships(Request $request): array
    {
        $relationships = [
            'brand' => BrandResource::class,
            'categories' => CategoryResource::class,
            'collections' => CollectionResource::class,
            'tags' => TagResource::class,
            'relatedProducts' => self::class,
        ];

        if ($this->canUseVariants()) {
            $relationships['variants'] = ProductVariantResource::class;
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
     * @return array<string, array<int, array<string, mixed>>>
     */
    private function optionsPayload(): array
    {
        if (! $this->canUseAttributes() || ! $this->resource->relationLoaded('options')) {
            return [];
        }

        return [
            'options' => $this->options
                ->map(fn (Attribute $option): array => [
                    'name' => $option->name,
                    'slug' => $option->slug,
                    'description' => $option->description,
                    'type' => $option->type->value,
                    'icon' => $option->icon,
                    'custom_value' => $option->custom_value ?? null,
                    'values' => $option->values
                        ->map(fn (AttributeValue $value): array => [
                            'key' => $value->key,
                            'value' => $value->value,
                            'position' => $value->position,
                            'swatch_url' => $value->swatch_url ?? null,
                        ])
                        ->values()
                        ->all(),
                ])
                ->values()
                ->all(),
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
}
