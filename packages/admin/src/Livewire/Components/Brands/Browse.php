<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Brands;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Repositories\Store\BrandRepository;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.components.brands.browse', [
            'total' => (new BrandRepository())->count(),
        ]);
    }
}
