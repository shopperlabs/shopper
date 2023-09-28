<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Products\Form;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\WithFileUploads;
use Shopper\Core\Events\Products\Updated;
use Shopper\Core\Exceptions\GeneralException;
use Shopper\Core\Repositories\Store\BrandRepository;
use Shopper\Core\Repositories\Store\CategoryRepository;
use Shopper\Core\Repositories\Store\CollectionRepository;
use Shopper\Core\Traits\Attributes\WithChoicesBrands;
use Shopper\Core\Traits\Attributes\WithSeoAttributes;
use Shopper\Core\Traits\Attributes\WithUploadProcess;
use Shopper\Http\Livewire\AbstractBaseComponent;
use Shopper\Http\Livewire\Components\Products\WithAttributes;

class Edit extends AbstractBaseComponent
{
    use WithAttributes;
    use WithChoicesBrands;
    use WithFileUploads;
    use WithSeoAttributes;
    use WithUploadProcess;

    public $product;

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
        $this->images = $product->getMedia(config('shopper.core.storage.collection_name'));
    }

    public function onTrixValueUpdate(string $value): void
    {
        $this->description = $value;
    }

    public function mediaDeleted(): void
    {
        $this->images = $this->product->getMedia(config('shopper.core.storage.collection_name'));
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
                    ->toMediaCollection(config('shopper.core.storage.collection_name'))
            );
        }

        if (collect($this->category_ids)->isNotEmpty()) {
            $this->product->categories()->sync($this->category_ids);
        }

        $this->product->collections()->sync($this->collection_ids);

        event(new Updated($this->product));

        $this->emit('productHasUpdated', $this->productId);

        Notification::make()
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
