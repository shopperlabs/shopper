<?php

namespace Shopper\Framework\Components\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\InventoryRepository;

class InventoryHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $direction = 'desc';
    public $inventory = 0;
    public $name = '';

    public function mount()
    {
        $inventory = (new InventoryRepository())->where('is_default', true)->first();
        $this->inventory = $inventory ? $inventory->id : 0;
        $this->name = $inventory ? $inventory->name : '';
    }

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

    public function setInventory($id, string $name)
    {
        $this->name = $name;
        $this->inventory = $id;
    }

    public function render()
    {
        $value = $this->inventory;
        $inventories = (new InventoryRepository())->get(['name', 'id']);
        $products = (new ProductRepository)
            ->with('inventoryHistories')
            ->whereHas('inventoryHistories', function (Builder $query) use ($value) {
                $query->where('inventory_id', $value);
            })
            ->where('name', '%' . $this->search . '%', 'like')
            ->orderBy('created_at', $this->direction)
            ->paginate(10);

        return view('shopper::components.livewire.inventories.list', compact('products', 'inventories'));
    }
}
