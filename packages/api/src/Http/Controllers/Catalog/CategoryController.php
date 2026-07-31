<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Concerns\LoadsStock;
use Shopper\Api\Concerns\ResolvesChannel;
use Shopper\Api\Http\Resources\CategoryResource;
use Shopper\Core\Models\Contracts\Category;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class CategoryController
{
    use BuildsApiQueries;
    use LoadsStock;
    use ResolvesChannel;

    public function index(): JsonApiResourceCollection
    {
        $query = $this->withMediaIfSupported(resolve(Category::class)::query()->enabled());

        $categories = $this->paginated('category', $query);

        $this->loadStockThroughRelation($categories->getCollection());

        return CategoryResource::collection($categories);
    }

    public function show(string $slug): JsonApiResource
    {
        $category = $this->withPublicProducts(
            $this->withMediaIfSupported(resolve(Category::class)::query()->enabled())
                ->with($this->requestedIncludeLoads('category'))
        )
            ->where('slug', $slug)
            ->firstOrFail();

        $this->loadStockThroughRelation(collect([$category]));

        return CategoryResource::make($category);
    }
}
