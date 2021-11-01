<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Events\Products\ProductRemoved;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Traits\WithSorting;

class Browse extends Component
{
    public function render()
    {
        return view('shopper::livewire.products.browse', [
            'total' => (new ProductRepository())->count(),
        ]);
    }
}
