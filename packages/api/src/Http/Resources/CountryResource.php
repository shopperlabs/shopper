<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Api\Http\Includes\EnabledRelation;
use Shopper\Core\Models\Country;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

/**
 * @mixin Country
 */
class CountryResource extends JsonApiResource
{
    public static function filters(): array
    {
        return [
            AllowedFilter::partial('name'),
            AllowedFilter::exact('cca2'),
            AllowedFilter::exact('cca3'),
            AllowedFilter::exact('region'),
            AllowedFilter::exact('zone', 'zones.code'),
        ];
    }

    public static function sorts(): array
    {
        return ['name'];
    }

    public static function includes(): array
    {
        return [
            AllowedInclude::custom('zones', new EnabledRelation),
        ];
    }

    final public function toType(Request $request): string
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
            'zones' => ZoneResource::class,
        ];
    }
}
