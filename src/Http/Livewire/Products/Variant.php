<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Events\Products\ProductUpdated;
use Shopper\Framework\Repositories\InventoryRepository;
use Shopper\Framework\Traits\WithUploadProcess;
use WireUi\Traits\Actions;

class Variant extends Component
{
    use Actions,
        WithFileUploads,
        WithUploadProcess,
        WithAttributes;

    public Model $product;
    public Model $variant;
    public Collection $inventories;
    public string $currency;
    public $images = [];

    protected $listeners = [
        'mediaDeleted',
        'onVariantUpdated' => 'render',
    ];

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
        $this->images = $variant->getMedia(config('shopper.system.storage.disks.uploads'));
    }

    public function store()
    {
        $this->validate([
            'name' => [
                'required',
                'max:150',
                Rule::unique(shopper_table('products'), 'name')->ignore($this->variant->id),
            ],
            'files.*' => 'nullable|image|max:10024',
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

        if (collect($this->files)->isNotEmpty()) {
            collect($this->files)->each(
                fn ($file) => $this->variant->addMedia($file->getRealPath())
                    ->toMediaCollection(config('shopper.system.storage.disks.uploads'))
            );
        }

        event(new ProductUpdated($this->variant));

        $this->emitSelf('onVariantUpdated');

        $this->notification()->success(__('Updated'), __('Variant successfully updated!'));
    }

    /**
     * Listen when a file is removed from the storage
     * and update the user screen and remove image preview.
     */
    public function mediaDeleted()
    {
        $this->images = $this->variant->getMedia(config('shopper.system.storage.disks.uploads'));
    }

    public function render()
    {
        return view('shopper::livewire.products.variant', [
            'media' => $this->variant->getFirstMedia(config('shopper.system.storage.disks.uploads')),
        ]);
    }
}
