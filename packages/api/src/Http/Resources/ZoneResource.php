<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\Zone;

/**
 * @mixin Zone
 */
class ZoneResource extends JsonApiResource
{
    final public function toType(Request $request): string
    {
        return 'zones';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'currency_code' => $this->currency?->code,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    public function toRelationships(Request $request): array
    {
        return [
            'currency' => fn () => CurrencyResource::make($this->currency),
            'countries' => fn () => CountryResource::collection($this->countries),
        ];
    }
}
