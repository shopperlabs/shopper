<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Store;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\JsonApiResource;
use Shopper\Api\Http\Resources\JsonApiResourceCollection;
use Shopper\Api\Http\Resources\LegalResource;
use Shopper\Core\Models\Legal;

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
        $query = Legal::query()->enabled()->where('slug', $slug);

        $this->applyPublicIncludes('legal', $query);

        return LegalResource::make($query->firstOrFail());
    }
}
