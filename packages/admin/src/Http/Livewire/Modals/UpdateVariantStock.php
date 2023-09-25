<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Repositories\Ecommerce\ProductRepository;
use Shopper\Core\Repositories\InventoryHistoryRepository;
use Shopper\Core\Repositories\InventoryRepository;
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
            'inventories' => (new InventoryRepository())->all(),
        ]);
    }
}
