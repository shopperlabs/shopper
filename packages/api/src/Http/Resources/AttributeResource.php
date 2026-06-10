<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;

/**
 * @mixin Attribute
 */
final class AttributeResource extends JsonApiResource
{
    public function toType(Request $request): string
    {
        return 'attributes';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type->value,
            'is_enabled' => $this->is_enabled,
            'is_filterable' => $this->is_filterable,
            'is_searchable' => $this->is_searchable,
            'icon' => $this->icon,
            'values' => $this->values->map(fn (AttributeValue $value): array => [
                'key' => $value->key,
                'value' => $value->value,
                'position' => $value->position,
            ])->values()->all(),
        ];
    }
}
