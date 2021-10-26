<?php

namespace Shopper\Framework\Http\Livewire\Brands;

use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;

class Browse extends Component
{
    public function render()
    {
        return view('shopper::livewire.brands.browse', [
            'total' => (new BrandRepository())->count(),
        ]);
    }
}
