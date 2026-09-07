<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Api\Concerns\SerializesMedia;
use Shopper\Core\Models\Category;
use Shopper\Core\Queries\CategoryTree;

/**
 * @mixin Category
 */
class CategoryResource extends JsonApiResource
{
    use SerializesMedia;

    final public function toType(Request $request): string
    {
        return 'categories';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'position' => $this->position,
            'parent_id' => $this->parent?->public_id,
            'is_enabled' => $this->is_enabled,
            'depth' => resolve(CategoryTree::class)->depth($this->resource->getKey()),
            ...$this->productsCountPayload(),
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
            'parent' => self::class,
            'children' => self::class,
            'ancestors' => self::class,
            'products' => ProductResource::class,
        ];
    }

    /**
     * @return array<string, int>
     */
    private function productsCountPayload(): array
    {
        $raw = $this->resource->getAttributes();

        return array_key_exists('products_count', $raw)
            ? ['products_count' => (int) $raw['products_count']]
            : [];
    }
}
