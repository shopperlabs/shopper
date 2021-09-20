<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Milon\Barcode\Facades\DNS1DFacade;
use Shopper\Framework\Traits\WithStock;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\InventoryHistoryRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class Inventory extends Component
{
    use WithPagination;

    use WithAttributes;

    use WithStock;

    /**
     * Product Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $product;

    public $inventories;

    public function mount($product, $inventories, $defaultInventory)
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

    /**
     * Store/Update a entry to the storage.
     */
    public function store()
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

        $this->notify([
            'title' => __('Updated'),
            'message' => __('Product Stock attribute successfully updated!'),
        ]);
    }

    public function render()
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
