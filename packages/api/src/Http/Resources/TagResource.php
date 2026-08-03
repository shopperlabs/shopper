<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\ProductTag;

/**
 * @mixin ProductTag
 */
class TagResource extends JsonApiResource
{
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
            'products' => fn () => ProductResource::collection($this->products),
        ];
    }
}
