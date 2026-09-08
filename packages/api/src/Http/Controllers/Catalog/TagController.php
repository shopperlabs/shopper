<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\JsonApiResourceCollection;
use Shopper\Api\Http\Resources\TagResource;
use Shopper\Core\Models\ProductTag;

final class TagController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        return TagResource::collection($this->paginated('tag', ProductTag::query()));
    }
}
