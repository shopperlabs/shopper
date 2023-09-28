<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Products\Form;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Milon\Barcode\Facades\DNS1DFacade;
use Shopper\Core\Models\InventoryHistory;
use Shopper\Core\Traits\Attributes\WithStock;
use Shopper\Http\Livewire\Components\Products\WithAttributes;

class Inventory extends Component
{
    use WithAttributes;
    use WithPagination;
    use WithStock;

    public $product;

    public $inventories;

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

        $this->product->update([
            'sku' => $this->sku ?? null,
            'barcode' => $this->barcode ?? null,
            'security_stock' => $this->securityStock ?? null,
        ]);

        Notification::make()
            ->body(__('shopper::pages/products.notifications.stock_update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.products.forms.form-inventory', [
            'currentStock' => InventoryHistory::query()
                ->where('inventory_id', $this->inventory)
                ->where('stockable_id', $this->product->id)
                ->get()
                ->sum('quantity'),
            'histories' => InventoryHistory::query()
                ->where('inventory_id', $this->inventory)
                ->where('stockable_id', $this->product->id)
                ->orderByDesc('created_at')
                ->paginate(5),
            'barcodeImage' => $this->barcode
                ? DNS1DFacade::getBarcodeHTML($this->barcode, config('shopper.core.barcode_type')) // @phpstan-ignore-line
                : null,
        ]);
    }
}
