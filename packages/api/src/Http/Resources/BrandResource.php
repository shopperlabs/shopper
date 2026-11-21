<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Api\Concerns\SerializesMedia;
use Shopper\Api\Http\Includes\PublicProducts;
use Shopper\Core\Models\Brand;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

/**
 * @mixin Brand
 */
class BrandResource extends JsonApiResource
{
    use SerializesMedia;

    public static function filters(): array
    {
        return [
            AllowedFilter::partial('name'),
        ];
    }

    public static function sorts(): array
    {
        return ['name', 'position'];
    }

    public static function includes(): array
    {
        return [
            AllowedInclude::custom('products', new PublicProducts),
        ];
    }

    final public function toType(Request $request): string
    {
        return 'brands';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'website' => $this->website,
            'description' => $this->description,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'metadata' => $this->metadata,
            'thumbnail' => $this->thumbnailPayload(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    public function toRelationships(Request $request): array
    {
        return [
            'products' => ProductResource::class,
        ];
    }
}
