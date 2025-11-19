<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Models\Category;
use Shopper\Core\Repositories\CategoryRepository;

final class CategoryObserver
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
            /** @var Category|null $parent */
            $parent = (new CategoryRepository)
                ->getById($category->parent_id, ['slug']);

            if ($parent instanceof Category) {
                $category->fill(['slug' => $parent->slug.'-'.$category->name]);
            }
        }
    }
}
