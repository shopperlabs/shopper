<?php

namespace Shopper\Framework\Http\Components;

use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

class CategoryList extends Component
{
    public $categories;

    public function mount(CategoryRepository $repository)
    {
        $this->categories = $repository->all();
    }

    public function render()
    {
        return view('shopper::components.categories.list', [
            'categories' => $this->categories
        ]);
    }
}
