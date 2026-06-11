<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Geo;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\CurrencyResource;
use Shopper\Core\Models\Currency;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class CurrencyController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        return CurrencyResource::collection(
            $this->paginated('currency', Currency::query())
        );
    }

    public function show(string $code): JsonApiResource
    {
        return CurrencyResource::make(
            Currency::query()->where('code', mb_strtoupper($code))->firstOrFail()
        );
    }
}
