<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Geo;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\JsonApiResource;
use Shopper\Api\Http\Resources\JsonApiResourceCollection;
use Shopper\Api\Http\Resources\ZoneResource;
use Shopper\Core\Models\Zone;

final class ZoneController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        return ZoneResource::collection(
            $this->paginated(ZoneResource::class, Zone::query()->enabled()->with('currency'))
        );
    }

    public function show(string $code): JsonApiResource
    {
        $query = Zone::query()
            ->enabled()
            ->with('currency')
            ->where('code', $code);

        $this->applyPublicIncludes(ZoneResource::class, $query);

        return ZoneResource::make($query->firstOrFail());
    }
}
