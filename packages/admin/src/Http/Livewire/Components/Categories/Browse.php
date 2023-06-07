<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Categories;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Repositories\Ecommerce\CategoryRepository;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.categories.browse', [
            'total' => (new CategoryRepository())->count(),
        ]);
    }
}
