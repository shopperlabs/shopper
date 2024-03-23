<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Core\Events\Products\Updated as ProductUpdated;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Traits\Attributes\WithUploadProcess;

class Variant extends Component
{
    use WithAttributes;
    use WithFileUploads;
    use WithUploadProcess;

    public $product;

    public $variant;

    public Collection $inventories;

    public string $currency;

    public $images = [];

    protected $listeners = [
        'mediaDeleted',
        'onVariantUpdated' => 'render',
    ];

    public function mount($product, $variant, string $currency): void
    {
        $this->inventories = Inventory::query()->select(['name', 'id'])->get();
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
        $this->images = $variant->getMedia(config('shopper.core.storage.collection_name'));
    }

    public function store(): void
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
                    ->toMediaCollection(config('shopper.core.storage.collection_name'))
            );
        }

        event(new ProductUpdated($this->variant));

        $this->emitSelf('onVariantUpdated');

        Notification::make()
            ->body(__('shopper::pages/products.notifications.variation_update'))
            ->success()
            ->send();
    }

    public function mediaDeleted(): void
    {
        $this->images = $this->variant->getMedia(config('shopper.core.storage.collection_name'));
    }

    public function render(): View
    {
        return view('shopper::livewire.products.variant', [
            'media' => $this->variant->getFirstMedia(config('shopper.core.storage.collection_name')),
        ]);
    }
}
