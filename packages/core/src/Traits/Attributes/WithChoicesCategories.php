<?php

declare(strict_types=1);

namespace Shopper\Core\Traits\Attributes;

use Shopper\Core\Repositories\Ecommerce\CategoryRepository;

trait WithChoicesCategories
{
    public array $selectedCategory = [];

    public function updatedSelectedCategory(array $choice): void
    {
        if (count($choice) > 0 && '0' !== $choice['value']) {
            $this->parent_id = (int) $choice['value'];
            $this->parent = (new CategoryRepository())->getById($this->parent_id);
        } else {
            $this->parent_id = null;
            $this->parent = null;
        }
    }
}
