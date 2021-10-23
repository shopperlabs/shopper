<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Livewire\WithFileUploads;
use Shopper\Framework\Events\Products\ProductUpdated;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Traits\WithProductAssociations;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Traits\WithUploadProcess;

class Edit extends AbstractBaseComponent
{
    use WithAttributes,
        WithFileUploads,
        WithProductAssociations,
        WithUploadProcess,
        WithSeoAttributes;

    public $product;

    public int $productId;
    public string $currency;
    public $images = [];

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'mediaDeleted',
    ];

    public function mount($product, string $currency)
    {
        $this->product = $product;
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->brand_id = $product->brand_id;
        $this->description = $product->description;
        $this->isVisible = $product->is_visible;
        $this->price_amount = $product->price_amount;
        $this->old_price_amount = $product->old_price_amount;
        $this->cost_amount = $product->cost_amount;
        $this->publishedAt = $product->published_at->format('Y-m-d');
        $this->publishedAtFormatted = $product->published_at->toRfc7231String();
        $this->associateCollections = $this->collection_ids = $product->collections->pluck('id')->toArray();
        $this->associateCategories = $this->category_ids = $product->categories->pluck('id')->toArray();
        $this->currency = $currency;
        $this->images = $product->getMedia(config('shopper.system.storage.disks.uploads'));
    }

    public function onTrixValueUpdate($value)
    {
        $this->description = $value;
    }

    public function mediaDeleted()
    {
        $this->images = $this->product->getMedia(config('shopper.system.storage.disks.uploads'));
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'files.*' => 'nullable|image|max:5120',
            'brand_id' => 'nullable|integer|exists:' . shopper_table('brands') . ',id',
        ];
    }

    public function store(): void
    {
        $this->validate($this->rules());

        $this->product->update([
            'name' => $this->name,
            'slug' => $this->name,
            'description' => $this->description,
            'is_visible' => $this->isVisible,
            'old_price_amount' => $this->old_price_amount,
            'price_amount' => $this->price_amount,
            'cost_amount' => $this->cost_amount,
            'published_at' => $this->publishedAt,
            'brand_id' => $this->brand_id,
        ]);

        if (collect($this->files)->isNotEmpty()) {
            collect($this->files)->each(
                fn ($file) => $this->product->addMedia($file->getRealPath())
                    ->toMediaCollection(config('shopper.system.storage.disks.uploads'))
            );
        }

        if (collect($this->associateCategories)->isNotEmpty()) {
            $this->product->categories()->sync($this->associateCategories);
        }

        if (collect($this->associateCollections)->isNotEmpty()) {
            $this->product->collections()->sync($this->associateCollections);
        }

        event(new ProductUpdated($this->product));

        $this->emit('productHasUpdated', $this->productId);

        $this->notify([
            'title' => __('Updated'),
            'message' => __('Product successfully updated!'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.products.forms.form-edit', [
            'brands' => (new BrandRepository())
                ->makeModel()
                ->scopes('enabled')
                ->select('name', 'id')
                ->get(),
            'categories' => (new CategoryRepository())
                ->makeModel()
                ->scopes('enabled')
                ->select('name', 'id')
                ->get(),
            'collections' => (new CollectionRepository())->get(['name', 'id']),
        ]);
    }
}
