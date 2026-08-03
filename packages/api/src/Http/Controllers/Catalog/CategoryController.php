<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Concerns\LoadsStock;
use Shopper\Api\Concerns\ResolvesChannel;
use Shopper\Api\Http\Includes\EnabledRelation;
use Shopper\Api\Http\Includes\SubtreeProductsCount;
use Shopper\Api\Http\Resources\CategoryResource;
use Shopper\Core\Models\Contracts\Category as CategoryContract;
use Shopper\Core\Queries\CategoryTree;
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
        $this->loadDepth($categories->getCollection());
        $this->loadSubtreeProductsCount($categories->getCollection());

        return CategoryResource::collection($categories);
    }

    public function tree(): JsonResponse
    {
        $hidden = resolve(CategoryTree::class)->hiddenIds();

        $rows = resolve(CategoryContract::class)::query()
            ->when($hidden !== [], fn (Builder $query) => $query->whereKeyNot($hidden))
            ->orderBy('position')
            ->toBase()
            ->get(['id', 'public_id', 'name', 'slug', 'position', 'parent_id']);

        $byParent = $rows->groupBy(fn (object $row) => $row->parent_id ?? 0);

        return response()->json(['data' => $this->buildTree($byParent, 0)]);
    }

    public function show(string $slug): JsonApiResource
    {
        $query = $this->publicQuery()->with($this->requestedIncludeLoads('category'));

        $this->applyPublicIncludes('category', $query);

        $category = $query->where('slug', $slug)->firstOrFail();

        $this->loadStockThroughRelation(collect([$category]));
        $this->loadDepth(collect([$category]));
        $this->loadSubtreeProductsCount(collect([$category]));

        return CategoryResource::make($category);
    }

    /**
     * @param  Collection<int|string, Collection<int, object>>  $byParent
     * @return array<int, array<string, mixed>>
     */
    private function buildTree(Collection $byParent, int|string $parent): array
    {
        $nodes = [];

        foreach ($byParent->get($parent) ?? [] as $row) {
            $nodes[] = [
                'id' => $row->public_id ?? (string) $row->id,
                'name' => $row->name,
                'slug' => $row->slug,
                'position' => $row->position,
                'children' => $this->buildTree($byParent, $row->id),
            ];
        }

        return $nodes;
    }

    /**
     * @param  Collection<int, Model>  $categories
     */
    private function loadDepth(Collection $categories): void
    {
        $tree = resolve(CategoryTree::class);

        $categories->each(
            fn (Model $category) => $category->setAttribute('depth', $tree->depth($category->getKey()))
        );
    }

    /**
     * @param  Collection<int, Model>  $categories
     */
    private function loadSubtreeProductsCount(Collection $categories): void
    {
        if ($this->requestedIncludes()->contains('products_count')) {
            SubtreeProductsCount::load($categories);
        }
    }

    /**
     * @return Builder<Model>
     */
    private function publicQuery(): Builder
    {
        $hidden = resolve(CategoryTree::class)->hiddenIds();

        $query = resolve(CategoryContract::class)::query()
            ->when($hidden !== [], fn (Builder $query) => $query->whereKeyNot($hidden))
            ->with(['parent' => EnabledRelation::constraint()]);

        return $this->withMediaIfSupported($query);
    }
}
