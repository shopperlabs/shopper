<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Geo;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\CurrencyResource;
use Shopper\Api\Http\Resources\JsonApiResource;
use Shopper\Api\Http\Resources\JsonApiResourceCollection;
use Shopper\Core\Models\Currency;

final class CurrencyController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        return CurrencyResource::collection(
            $this->paginated(CurrencyResource::class, Currency::query())
        );
    }

    public function show(string $code): JsonApiResource
    {
        $query = Currency::query()->where('code', mb_strtoupper($code));

        $this->applyPublicIncludes(CurrencyResource::class, $query);

        return CurrencyResource::make($query->firstOrFail());
    }
}
