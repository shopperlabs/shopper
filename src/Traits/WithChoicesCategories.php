<?php

namespace Shopper\Framework\Traits;

use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

trait WithChoicesCategories
{
    public $selectedCategory = [];

    public function updatedSelectedCategory($choice)
    {
        if (count($choice) > 0 && $choice['value'] !== '0') {
            $this->parent_id = (int) $choice['value'];
            $this->parent = (new CategoryRepository())->getById($this->parent_id);
        } else {
            $this->parent_id = null;
            $this->parent = null;
        }
    }
}
