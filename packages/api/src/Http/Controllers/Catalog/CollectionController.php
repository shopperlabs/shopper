<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\CollectionResource;
use Shopper\Core\Models\Contracts\Collection;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class CollectionController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        $query = $this->withMediaIfSupported(resolve(Collection::class)::query()->published());

        return CollectionResource::collection($this->paginated('collection', $query));
    }

    public function show(string $slug): JsonApiResource
    {
        $collection = $this->withMediaIfSupported(resolve(Collection::class)::query()->published())
            ->where('slug', $slug)
            ->firstOrFail();

        return CollectionResource::make($collection);
    }
}
