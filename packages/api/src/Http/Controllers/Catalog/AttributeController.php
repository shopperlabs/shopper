<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\AttributeResource;
use Shopper\Core\Models\Attribute;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class AttributeController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        $query = Attribute::query()
            ->enabled()
            ->isFilterable()
            ->whereIn('type', Attribute::fieldsWithValues())
            ->with('values');

        return AttributeResource::collection($this->paginated('attribute', $query));
    }
}
