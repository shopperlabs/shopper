<?php

namespace Shopper\Framework\Http\Components\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class ProductList extends Component
{
    use WithPagination;

    /**
     * Search.
     *
     * @var string
     */
    public $search = '';

    /**
     * Sort direction.
     *
     * @var string
     */
    public $direction = 'desc';

    public function paginationView()
    {
        return 'shopper::components.livewire.wire-pagination-links';
    }

    /**
     * Sort results.
     *
     * @param  string  $value
     */
    public function sort($value)
    {
        $this->direction = $value === 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        $products = (new ProductRepository())
            ->where('name', '%'. $this->search .'%', 'like')
            ->orderBy('created_at', $this->direction)
            ->paginate(10);

        return view('shopper::components.livewire.products.list', compact('products'));
    }
}
