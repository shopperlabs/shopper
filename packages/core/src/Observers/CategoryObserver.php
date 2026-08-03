<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Shopper\Core\Models\Contracts\Category;
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

    public function saved(Category $category): void
    {
        CategoryTree::flush();
    }

    public function deleted(Category $category): void
    {
        CategoryTree::flush();
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
