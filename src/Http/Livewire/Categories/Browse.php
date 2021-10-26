<?php

namespace Shopper\Framework\Http\Livewire\Categories;

use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

class Browse extends Component
{
    public function render()
    {
        return view('shopper::livewire.categories.browse', [
            'total' => (new CategoryRepository())->count(),
        ]);
    }
}
