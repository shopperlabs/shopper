<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Events\Products\ProductUpdated;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\InventoryHistoryRepository;
use Shopper\Framework\Repositories\InventoryRepository;
use Shopper\Framework\Traits\WithUploadProcess;

class Variant extends Component
{
    use WithFileUploads,
        WithUploadProcess,
        WithAttributes;

    /**
     * Upload listeners.
     *
     * @var string[]
     */
    protected $listeners = ['fileDeleted'];

    /**
     * Product Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $product;

    /**
     * Variation Model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $variant;

    /**
     * All locations available on the store.
     *
     * @var mixed
     */
    public $inventories;

    /**
     * Confirm action to delete product.
     *
     * @var bool
     */
    public $confirmDeleteProduct = false;

    /**
     * Display inventory modal.
     *
     * @var bool
     */
    public $showModalInventories = false;

    /**
     * Component Mount instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $product
     * @param  \Illuminate\Database\Eloquent\Model  $variant
     * @return void
     */
    public function mount($product, $variant)
    {
        $this->inventories = (new InventoryRepository())->get(['name', 'id']);
        $this->product = $product;
        $this->variant = $variant;
        $this->name = $variant->name;
        $this->sku = $variant->sku;
        $this->barcode = $variant->barcode;
        $this->securityStock = $variant->security_stock;
        $this->price_amount = $variant->price_amount;
        $this->old_price_amount = $variant->old_price_amount;
        $this->cost_amount = $variant->cost_amount;
    }

    /**
     * Launch modale to remove product.
     *
     * @param  string  $type
     * @return void
     */
    public function openModal(string $type = 'delete')
    {
        if ($type === 'delete') {
            $this->confirmDeleteProduct = true;
        } else {
            $this->showModalInventories = true;
        }
    }

    /**
     * Close modale.
     *
     * @param  string  $type
     * @return void
     */
    public function closeModal(string $type = 'delete')
    {
        if ($type === 'delete') {
            $this->confirmDeleteProduct = false;
        } else {
            $this->showModalInventories = false;
        }
    }

    /**
     * Update variant record in the database.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'name' => [
                'required',
                'max:150',
                Rule::unique(shopper_table('products'), 'name')->ignore($this->variant->id),
            ],
            'file' => 'nullable|image|max:1024',
            'sku'  => [
                'nullable',
                Rule::unique(shopper_table('products'), 'sku')->ignore($this->variant->id),
            ],
            'barcode'  => [
                'nullable',
                Rule::unique(shopper_table('products'), 'barcode')->ignore($this->variant->id),
            ],
        ]);

        (new ProductRepository())->getById($this->variant->id)->update([
            'name' => $this->name,
            'old_price_amount' => $this->old_price_amount ?? null,
            'price_amount' => $this->price_amount ?? null,
            'cost_amount' => $this->cost_amount ?? null,
            'sku' => $this->sku ?? null,
            'barcode' => $this->barcode ?? null,
            'security_stock' => $this->securityStock ?? null,
        ]);

        if ($this->file) {
            $this->uploadFile(config('shopper.system.models.product'), $this->variant->id);
        }

        event(new ProductUpdated($this->variant));

        $this->notify([
            'title' => __("Updated"),
            'message' => __("Variant successfully updated!"),
        ]);
    }

    /**
     * Listen when a file is removed from the storage
     * and update the user screen and remove image preview.
     *
     * @return void
     */
    public function fileDeleted()
    {
        $this->media = null;
    }

    /**
     * Removed a product to the storage.
     *
     * @throws \Exception
     */
    public function destroy()
    {
        (new ProductRepository())->getById($this->variant->id)->delete();

        session()->flash('success', __("The variation has been correctly removed!"));
        $this->redirectRoute('shopper.products.edit', $this->product);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.products.variant', [
            'currency' => shopper_currency(),
            'media' => $this->variant->files->isNotEmpty()
                ? $this->variant->files->first()
                : null,
            'histories' => (new InventoryHistoryRepository())
                ->with('inventory')
                ->where('stockable_id', $this->variant->id)
                ->orderBy('created_at', 'desc')
                ->paginate(5),
        ]);
    }
}
