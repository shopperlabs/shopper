<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Milon\Barcode\Facades\DNS1DFacade;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\InventoryHistoryRepository;
use Shopper\Framework\Traits\WithStock;

class Inventory extends Component
{
    use WithPagination;
    use WithAttributes;
    use WithStock;

    public Model $product;

    public Collection $inventories;

    public function mount($product, $inventories, $defaultInventory): void
    {
        $this->inventories = $inventories;
        $this->inventory = $defaultInventory;
        $this->product = $product;
        $this->stock = $product->stock;
        $this->realStock = $product->stock;
        $this->sku = $product->sku;
        $this->barcode = $product->barcode;
        $this->securityStock = $product->security_stock;
    }

    public function paginationView(): string
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    public function store(): void
    {
        $this->validate([
            'sku' => [
                'nullable',
                Rule::unique(shopper_table('products'), 'sku')->ignore($this->product->id),
            ],
            'barcode' => [
                'nullable',
                Rule::unique(shopper_table('products'), 'barcode')->ignore($this->product->id),
            ],
        ]);

        (new ProductRepository())->getById($this->product->id)->update([
            'sku' => $this->sku ?? null,
            'barcode' => $this->barcode ?? null,
            'security_stock' => $this->securityStock ?? null,
        ]);

        Notification::make()
            ->title(__('shopper::layout.status.updated'))
            ->body(__('shopper::pages/products.notifications.stock_update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.products.forms.form-inventory', [
            'currentStock' => (new InventoryHistoryRepository())
                ->where('inventory_id', $this->inventory)
                ->where('stockable_id', $this->product->id)
                ->get()
                ->sum('quantity'),
            'histories' => (new InventoryHistoryRepository())
                ->where('inventory_id', $this->inventory)
                ->where('stockable_id', $this->product->id)
                ->orderBy('created_at', 'desc')
                ->paginate(5),
            'barcodeImage' => $this->barcode
                ? DNS1DFacade::getBarcodeHTML($this->barcode, config('shopper.system.barcode_type'))
                : null,
        ]);
    }
}
