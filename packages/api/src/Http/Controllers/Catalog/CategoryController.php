<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\CategoryResource;
use Shopper\Core\Models\Contracts\Category;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class CategoryController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        $query = $this->withMediaIfSupported(resolve(Category::class)::query()->enabled());

        return CategoryResource::collection($this->paginated('category', $query));
    }

    public function show(string $slug): JsonApiResource
    {
        $category = $this->withMediaIfSupported(resolve(Category::class)::query()->enabled())
            ->with($this->requestedIncludeLoads('category'))
            ->where('slug', $slug)
            ->firstOrFail();

        return CategoryResource::make($category);
    }
}
