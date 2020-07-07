<?php

namespace Shopper\Framework\Http\Components\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class InventoryHistory extends Component
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
        $products = (new ProductRepository)
            ->with(['inventoryHistories'])
            ->where('name', '%' . $this->search . '%', 'like')
            ->orderBy('created_at', $this->direction)
            ->paginate(10);

        return view('shopper::components.livewire.inventories.list', compact('products'));
    }
}
