<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\Country;

/**
 * @mixin Country
 */
final class CountryResource extends JsonApiResource
{
    public function toType(Request $request): string
    {
        return 'countries';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'translated_name' => $this->translated_name,
            'name_official' => $this->name_official,
            'region' => $this->region,
            'subregion' => $this->subregion,
            'cca2' => $this->cca2,
            'flag' => $this->svg_flag,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }

    public function toRelationships(Request $request): array
    {
        return [
            'zones' => fn () => ZoneResource::collection($this->zones),
        ];
    }
}
