<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Api\Http\Includes\PublicProducts;
use Shopper\Core\Models\ProductTag;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

/**
 * @mixin ProductTag
 */
class TagResource extends JsonApiResource
{
    public static function filters(): array
    {
        return [
            AllowedFilter::partial('name'),
        ];
    }

    public static function sorts(): array
    {
        return ['name'];
    }

    public static function includes(): array
    {
        return [
            AllowedInclude::custom('products', new PublicProducts),
        ];
    }

    final public function toType(Request $request): string
    {
        return 'tags';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
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
