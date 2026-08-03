<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Shopper\Api\Concerns\SerializesMedia;
use Shopper\Core\Models\Category;

/**
 * @mixin Category
 *
 * @property-read ?int $depth
 */
final class CategoryResource extends JsonApiResource
{
    use SerializesMedia;

    public function toType(Request $request): string
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
            'depth' => $this->depth,
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
            'parent' => fn () => CategoryResource::make($this->parent),
            'children' => fn () => CategoryResource::collection($this->children),
            'ancestors' => fn () => CategoryResource::collection(
                $this->ancestors->sortBy('depth')->values()->each(function (Model $ancestor): void {
                    $ancestor->setAttribute('depth', null);
                })
            ),
            'products' => fn () => ProductResource::collection($this->products),
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
