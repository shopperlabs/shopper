<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use Shopper\Framework\Exports\ProductInventoryExport;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\InventoryHistoryRepository;
use Shopper\Framework\Repositories\InventoryRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Inventory extends Component
{
    use WithPagination, WithAttributes;

    /**
     * Product Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $product;

    /**
     * Product id.
     *
     * @var int
     */
    public $productId;

    /**
     * Stock product value
     *
     * @var int
     */
    public $stock;

    /**
     * @var int
     */
    public $value = 0;

    /**
     * @var int
     */
    public $realStock = 0;

    /**
     * All locations available on the store.
     *
     * @var mixed
     */
    public $inventories;

    /**
     * Default inventory id.
     *
     * @var int
     */
    public $inventory = 0;

    /**
     * Component mount instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $product
     */
    public function mount($product)
    {
        $this->inventories = (new InventoryRepository())->get(['name', 'id']);
        $this->inventory = (new InventoryRepository())->where('is_default', true)->first()->id;
        $this->product = $product;
        $this->productId = $product->id;
        $this->stock = $product->stock;
        $this->realStock = $product->stock;
        $this->sku = $product->sku;
        $this->barcode = $product->barcode;
        $this->securityStock = $product->security_stock;
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
     * Update stock value.
     *
     * @param  $value
     */
    public function updatedValue($value)
    {
        $this->value = $value;
        $this->realStock = $this->stock + (int) $this->value;
    }

    /**
     * Store/Update a entry to the storage.
     *
     * @return void
     */
    public function store()
    {
        $this->validate($this->rules());

        (new ProductRepository())->getById($this->productId)->update([
            'sku' => $this->sku ?? null,
            'barcode' => $this->barcode ?? null,
            'security_stock' => $this->securityStock ?? null,
        ]);

        $this->emit('productHasUpdated', $this->productId);

        $this->notify([
            'title' => __("Updated"),
            'message' => __("Product Stock attribute successfully updated!"),
        ]);
    }

    /**
     * Component validation rules.
     *
     * @return array[]|string[]
     */
    protected function rules()
    {
        return [
            'sku'  => [
                'nullable',
                Rule::unique(shopper_table('products'), 'sku')->ignore($this->productId),
            ],
            'barcode'  => [
                'nullable',
                Rule::unique(shopper_table('products'), 'barcode')->ignore($this->productId),
            ],
        ];
    }

    /**
     * Increment product stock.
     *
     * @return void
     */
    public function incrementStock()
    {
        $this->validate(['value' => 'required|integer']);
        $this->value++;
        $this->realStock = $this->stock + (int) $this->value;
    }

    /**
     * Decrement product stock.
     *
     * @return void
     */
    public function decrementStock()
    {
        if ($this->realStock === 0) {
            return;
        }

        $this->validate(['value' => 'required|integer']);
        $this->value--;
        $this->realStock = $this->stock + (int) $this->value;
    }

    /**
     * Update stock.
     *
     * @return void
     */
    public function updateCurrentStock()
    {
        if ($this->value === 0) {
            return;
        }

        $this->validate(['value' => 'required|integer']);

        if ($this->realStock >= $this->stock) {
            $this->product->increaseStock(
                $this->inventory,
                $this->value,
                [
                    'event' => __('Manually added'),
                    'old_quantity' => $this->value,
                ]
            );
        } else {
            $this->product->decreaseStock(
                $this->inventory,
                $this->value,
                [
                    'event' => __('Manually removed'),
                    'old_quantity' => $this->value,
                ]
            );
        }

        $this->value = 0;
        $this->realStock = $this->stock = $this->product->stock;

        $this->notify([
            'title' => __('Updated'),
            'message' => __("Stock successfully Updated"),
        ]);
    }

    /**
     * Export default product stock movement.
     *
     * @return Response|BinaryFileResponse
     */
    public function export()
    {
        return (new ProductInventoryExport())
            ->forProduct($this->productId)
            ->download('product-stock-movements.xlsx', Excel::XLSX);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.products.forms.form-inventory', [
            'currentStock' => (new InventoryHistoryRepository())
                ->where('inventory_id', $this->inventory)
                ->where('stockable_id', $this->productId)
                ->get()
                ->sum('quantity'),
            'histories' => (new InventoryHistoryRepository())
                ->where('inventory_id', $this->inventory)
                ->where('stockable_id', $this->productId)
                ->orderBy('created_at', 'desc')
                ->paginate(5),
        ]);
    }
}
