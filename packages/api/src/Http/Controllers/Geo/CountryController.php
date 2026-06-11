<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Geo;

use Illuminate\Database\Eloquent\Builder;
use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\CountryResource;
use Shopper\Core\Models\Country;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class CountryController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        return CountryResource::collection(
            $this->paginated('country', Country::query())
        );
    }

    public function show(string $code): JsonApiResource
    {
        $code = mb_strtoupper($code);

        $country = Country::query()
            ->with($this->requestedIncludeLoads('country'))
            ->where(fn (Builder $query) => $query->where('cca2', $code)->orWhere('cca3', $code))
            ->firstOrFail();

        return CountryResource::make($country);
    }
}
