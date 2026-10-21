<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Shopper\Core\Models\Contracts\Category;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Queries\CategoryTree;

class CategoryObserver
{
    public function creating(Category $category): void
    {
        $this->ensureParentSlugIsCorrectlySet($category);
    }

    public function updating(Category $category): void
    {
        $this->ensureTheParentIsNotADescendant($category);
        $this->ensureParentSlugIsCorrectlySet($category);
    }

    public function saving(Category $category): void
    {
        CategoryTree::flush();
    }

    public function updated(Category $category): void
    {
        if ($category->wasChanged(['name', 'is_enabled', 'parent_id'])) {
            $this->reindexSubtree($category, $category->descendantsAndSelf()->pluck('id')->all());
        }
    }

    public function deleting(Category $category): void
    {
        $category->load('descendantsAndSelf');
    }

    public function deleted(Category $category): void
    {
        $this->reindexSubtree($category, $category->descendantsAndSelf->modelKeys());
    }

    /**
     * @param  array<int, int|string>  $categoryIds
     */
    private function reindexSubtree(Category $category, array $categoryIds): void
    {
        DB::afterCommit(function () use ($category, $categoryIds): void {
            CategoryTree::flush();

            [$visible, $hidden] = resolve(Category::class)::query()
                ->whereKey($categoryIds)
                ->get()
                ->partition
                ->shouldBeSearchable();

            $visible->searchable();
            $hidden->unsearchable();

            $products = resolve(Product::class)::query()->whereIn('id', DB::table(shopper_table('product_has_relations'))
                ->select('product_id')
                ->where('productable_type', $category->getMorphClass())
                ->whereIn('productable_id', $categoryIds));

            resolve(Product::class)->indexer()->makeAllSearchableUsing($products)->searchable();
        });
    }

    private function ensureTheParentIsNotADescendant(Category $category): void
    {
        if (! $category instanceof Model || ! $category->isDirty('parent_id') || blank($category->getAttribute('parent_id'))) {
            return;
        }

        $ancestor = (int) $category->getAttribute('parent_id');
        $visited = [];

        while ($ancestor !== 0 && ! in_array($ancestor, $visited, true)) {
            if ($ancestor === (int) $category->getKey()) {
                throw new RuntimeException('A category cannot be moved under one of its own descendants.');
            }

            $visited[] = $ancestor;
            $ancestor = (int) resolve(Category::class)::query()->whereKey($ancestor)->value('parent_id');
        }
    }

    /**
     * Ensure that the parent slug is present on the category slug is selected
     * slug is "parent_slug_category_slug" when a parent category is choose
     */
    private function ensureParentSlugIsCorrectlySet(Category $category): void
    {
        if (filled($category->parent_id)) {
            $parent = resolve(Category::class)::query()->find($category->parent_id);

            if ($parent instanceof Category) {
                $category->fill(['slug' => $parent->slug.'-'.$category->name]);
            }
        }
    }
}
