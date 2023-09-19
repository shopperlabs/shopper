<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Products;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.products.browse', [
            'total' => (new ProductRepository())->count(),
        ]);
    }
}
