<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Store;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\LegalResource;
use Shopper\Core\Models\Legal;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class LegalController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        return LegalResource::collection(
            $this->paginated('legal', Legal::query()->enabled())
        );
    }

    public function show(string $slug): JsonApiResource
    {
        return LegalResource::make(
            Legal::query()->enabled()->where('slug', $slug)->firstOrFail()
        );
    }
}
