<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Traits\WithUploadProcess;
use Shopper\Framework\Repositories\InventoryRepository;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class AddVariant extends ModalComponent
{
    use WithFileUploads, WithAttributes, WithUploadProcess;

    public int $productId;
    public string $currency;
    public $quantity;

    public function mount(int $productId, string $currency)
    {
        $this->productId = $productId;
        $this->currency = $currency;
    }

    public function save()
    {
        $this->validate($this->rules());

        $product = (new ProductRepository())->create([
            'name' => $this->name,
            'slug' => $this->name,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'is_visible' => true,
            'security_stock' => $this->securityStock,
            'old_price_amount' => $this->old_price_amount,
            'price_amount' => $this->price_amount,
            'cost_amount' => $this->cost_amount,
            'parent_id' => $this->productId,
        ]);

        if ($this->file) {
            $product->addMedia($this->file->getRealPath())->toMediaCollection(config('shopper.system.storage.disks.uploads'));
        }

        if ($this->quantity && count($this->quantity) > 0) {
            foreach ($this->quantity as $inventory => $value) {
                $product->mutateStock(
                    $inventory,
                    $value,
                    [
                        'event' => __('Initial inventory'),
                        'old_quantity' => $value,
                    ]
                );
            }
        }

        $this->notify([
            'title' => __('Added'),
            'message' => __('Product variation successfully added!'),
        ]);

        $this->emit('onVariantAdded');

        $this->closeModal();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|unique:' . shopper_table('products'),
            'sku' => 'nullable|unique:' . shopper_table('products'),
            'barcode' => 'nullable|unique:' . shopper_table('products'),
            'file' => 'nullable|image|max:1024',
        ];
    }

    public static function modalMaxWidth(): string
    {
        return '4xl';
    }

    public function render()
    {
        return view('shopper::livewire.modals.add-variant', [
            'inventories' => [],
        ]);
    }
}
