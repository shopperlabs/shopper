<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\WithFileUploads;
use Shopper\Framework\Events\Products\ProductUpdated;
use Shopper\Framework\Exceptions\GeneralException;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Traits\WithChoicesBrands;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Traits\WithUploadProcess;

class Edit extends AbstractBaseComponent
{
    use WithAttributes;
    use WithChoicesBrands;
    use WithFileUploads;
    use WithUploadProcess;
    use WithSeoAttributes;

    public Model $product;

    public int $productId;

    public string $currency;

    public array $category_ids = [];

    public array $collection_ids = [];

    public $images = [];

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'mediaDeleted',
    ];

    public function mount($product, string $currency): void
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
        $this->publishedAt = $product->published_at?->format('Y-m-d H:m');
        $this->publishedAtFormatted = $product->published_at?->toRfc7231String();
        $this->collection_ids = $product->collections->pluck('id')->toArray();
        $this->category_ids = $product->categories->pluck('id')->toArray();
        $this->selectedBrand = $product->brand_id ? [$product->brand_id] : [];
        $this->currency = $currency;
        $this->images = $product->getMedia(config('shopper.system.storage.disks.uploads'));
    }

    public function onTrixValueUpdate(string $value): void
    {
        $this->description = $value;
    }

    public function mediaDeleted(): void
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

        if (collect($this->category_ids)->isNotEmpty()) {
            $this->product->categories()->sync($this->category_ids);
        }

        $this->product->collections()->sync($this->collection_ids);

        event(new ProductUpdated($this->product));

        $this->emit('productHasUpdated', $this->productId);

        Notification::make()
            ->title(__('shopper::layout.status.updated'))
            ->body(__('shopper::pages/products.notifications.update'))
            ->success()
            ->send();
    }

    /**
     * @throws GeneralException
     */
    public function render(): View
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
                ->tree()
                ->orderBy('name')
                ->get()
                ->toTree(),
            'collections' => (new CollectionRepository())->get(['name', 'id']),
        ]);
    }
}
