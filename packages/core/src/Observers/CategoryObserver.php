<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Models\Contracts\Category;

class CategoryObserver
{
    public function creating(Category $category): void
    {
        $this->ensureParentSlugIsCorrectlySet($category);
    }

    public function updating(Category $category): void
    {
        $this->ensureParentSlugIsCorrectlySet($category);
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
