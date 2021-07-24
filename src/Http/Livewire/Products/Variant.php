<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Shopper\Framework\Traits\WithUploadProcess;
use Shopper\Framework\Events\Products\ProductUpdated;
use Shopper\Framework\Repositories\InventoryRepository;

class Variant extends Component
{
    use WithFileUploads;
    use WithUploadProcess;
    use WithAttributes;

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
     */
    public $inventories;

    /**
     * Shopper default currency.
     */
    public string $currency;

    protected $listeners = ['fileDeleted', 'onVariantUpdated' => 'render'];

    /**
     * Component Mount instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $product
     * @param \Illuminate\Database\Eloquent\Model $variant
     */
    public function mount($product, $variant, string $currency)
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
        $this->currency = $currency;
    }

    public function store()
    {
        $this->validate([
            'name' => [
                'required',
                'max:150',
                Rule::unique(shopper_table('products'), 'name')->ignore($this->variant->id),
            ],
            'file' => 'nullable|image|max:1024',
            'sku' => [
                'nullable',
                Rule::unique(shopper_table('products'), 'sku')->ignore($this->variant->id),
            ],
            'barcode' => [
                'nullable',
                Rule::unique(shopper_table('products'), 'barcode')->ignore($this->variant->id),
            ],
        ]);

        $this->variant->update([
            'name' => $this->name,
            'slug' => $this->name,
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

        $this->emitSelf('onVariantUpdated');

        $this->notify([
            'title' => __('Updated'),
            'message' => __('Variant successfully updated!'),
        ]);
    }

    /**
     * Listen when a file is removed from the storage
     * and update the user screen and remove image preview.
     */
    public function fileDeleted()
    {
        $this->media = null;
    }

    public function render()
    {
        return view('shopper::livewire.products.variant', [
            'media' => $this->variant->files->isNotEmpty()
                ? $this->variant->files->first()
                : null,
        ]);
    }
}
