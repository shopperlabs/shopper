<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Products;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\InventoryHistory;
use Shopper\Core\Traits\Attributes\WithStock;

class VariantStock extends Component
{
    use WithPagination;
    use WithStock;

    public $product;

    public function mount($variant): void
    {
        $this->product = $variant;
        $this->stock = $variant->stock;
        $this->realStock = $variant->stock;
    }

    public function paginationView(): string
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    public function render(): View
    {
        return view('shopper::livewire.products.variant-stock', [
            'currentStock' => InventoryHistory::query()
                ->where('inventory_id', $this->inventory)
                ->where('stockable_id', $this->product->id)
                ->get()
                ->sum('quantity'),
            'histories' => InventoryHistory::query()
                ->where('inventory_id', $this->inventory)
                ->where('stockable_id', $this->product->id)
                ->orderByDesc('created_at')
                ->paginate(3),
            'inventories' => Inventory::all(),
        ]);
    }
}
