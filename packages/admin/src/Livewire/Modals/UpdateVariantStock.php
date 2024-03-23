<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Illuminate\Contracts\View\View;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\InventoryHistory;
use Shopper\Core\Repositories\Store\ProductRepository;
use Shopper\Core\Traits\Attributes\WithStock;

class UpdateVariantStock extends ModalComponent
{
    use WithPagination;
    use WithStock;

    public $product;

    public function mount(int $id): void
    {
        $this->product = $variant = (new ProductRepository())->getById($id);
        $this->stock = $variant->stock; // @phpstan-ignore-line
        $this->realStock = $variant->stock; // @phpstan-ignore-line
    }

    public static function modalMaxWidth(): string
    {
        return '4xl';
    }

    public function paginationView(): string
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.update-variant-stock', [
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
