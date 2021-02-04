<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\InventoryHistoryRepository;
use Shopper\Framework\Traits\WithStock;

class VariantStock extends Component
{
    use WithPagination, WithStock;

    /**
     * Variation Model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $product;

    /**
     * Component Mount instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $variant
     * @return void
     */
    public function mount($variant)
    {
        $this->loadInventories();

        $this->product = $variant;
        $this->stock = $variant->stock;
        $this->realStock = $variant->stock;
    }

    /**
     * Custom Livewire pagination view.
     *
     * @return string
     */
    public function paginationView()
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.products.variant-stock', [
            'currentStock' => (new InventoryHistoryRepository())
                ->where('inventory_id', $this->inventory)
                ->where('stockable_id', $this->product->id)
                ->get()
                ->sum('quantity'),
            'histories' => (new InventoryHistoryRepository())
                ->where('inventory_id', $this->inventory)
                ->where('stockable_id', $this->product->id)
                ->orderBy('created_at', 'desc')
                ->paginate(3),
        ]);
    }
}
