<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Brands;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.brands.browse', [
            'total' => (new BrandRepository())->count(),
        ]);
    }
}
