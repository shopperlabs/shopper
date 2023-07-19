<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Brands;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Repositories\Ecommerce\BrandRepository;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.components.brands.browse', [
            'total' => (new BrandRepository())->count(),
        ]);
    }
}
