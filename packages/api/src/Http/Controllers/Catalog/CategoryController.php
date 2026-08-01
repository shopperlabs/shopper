<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Concerns\LoadsStock;
use Shopper\Api\Concerns\ResolvesChannel;
use Shopper\Api\Http\Includes\EnabledRelation;
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
        $categories = $this->paginated('category', $this->publicQuery());

        $this->loadStockThroughRelation($categories->getCollection());

        return CategoryResource::collection($categories);
    }

    public function show(string $slug): JsonApiResource
    {
        $query = $this->publicQuery()->with($this->requestedIncludeLoads('category'));

        $this->applyPublicIncludes('category', $query);

        $category = $query->where('slug', $slug)->firstOrFail();

        $this->loadStockThroughRelation(collect([$category]));

        return CategoryResource::make($category);
    }

    /**
     * The parent is eager-loaded for the `parent_id` attribute, through the
     * same constraint as the include so a disabled parent is reported as no
     * parent rather than as a node the client cannot fetch.
     *
     * @return Builder<Model>
     */
    private function publicQuery(): Builder
    {
        return $this->withMediaIfSupported(
            resolve(Category::class)::query()
                ->enabled()
                ->with(['parent' => EnabledRelation::constraint()])
        );
    }
}
