<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Concerns\ResolvesChannel;
use Shopper\Api\Http\Resources\CollectionResource;
use Shopper\Api\Http\Resources\JsonApiResource;
use Shopper\Api\Http\Resources\JsonApiResourceCollection;
use Shopper\Core\Models\Contracts\Collection;

final class CollectionController
{
    use BuildsApiQueries;
    use ResolvesChannel;

    public function index(): JsonApiResourceCollection
    {
        $query = $this->withMediaIfSupported(resolve(Collection::class)::query()->published());

        return CollectionResource::collection($this->paginated('collection', $query));
    }

    public function show(string $slug): JsonApiResource
    {
        $query = $this->withMediaIfSupported(resolve(Collection::class)::query()->published());

        $this->applyPublicIncludes('collection', $query);

        return CollectionResource::make($query->where('slug', $slug)->firstOrFail());
    }
}
