<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\TagResource;
use Shopper\Core\Models\ProductTag;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class TagController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        return TagResource::collection($this->paginated('tag', ProductTag::query()));
    }
}
