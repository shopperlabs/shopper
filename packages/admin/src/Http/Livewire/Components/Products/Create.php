<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Products;

use Illuminate\Contracts\View\View;
use Milon\Barcode\Facades\DNS1DFacade;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Repositories\Ecommerce\BrandRepository;
use Shopper\Core\Repositories\Ecommerce\CategoryRepository;
use Shopper\Core\Repositories\Ecommerce\CollectionRepository;
use Shopper\Core\Repositories\Ecommerce\ProductRepository;
use Shopper\Core\Traits\Attributes\WithChoicesBrands;
use Shopper\Core\Traits\Attributes\WithSeoAttributes;
use Shopper\Http\Livewire\AbstractBaseComponent;

class Create extends AbstractBaseComponent
{
    use WithAttributes;
    use WithSeoAttributes;
    use WithChoicesBrands;

    public ?Channel $defaultChannel = null;

    public array $category_ids = [];

    public array $collection_ids = [];

    public $quantity;

    public $files = [];

    public array $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    protected $listeners = [
        'productAdded',
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'shopper:filesUpdated' => 'onFilesUpdated',
    ];

    public function mount(): void
    {
        $this->defaultChannel = Channel::query()
            ->where('slug', 'web-store')
            ->first();
    }

    public function onTrixValueUpdate(string $value): void
    {
        $this->description = $value;
    }

    public function onFilesUpdated(array $files): void
    {
        $this->files = $files;
    }

    public function rules(): array
    {
        return [
            'name' => 'bail|required',
            'sku' => 'nullable|unique:' . shopper_table('products'),
            'barcode' => 'nullable|unique:' . shopper_table('products'),
            'brand_id' => 'nullable|integer|exists:' . shopper_table('brands') . ',id',
        ];
    }

    public function store(): void
    {
        $this->validate($this->rules());

        $product = (new ProductRepository())->create([
            'name' => $this->name,
            'slug' => $this->name,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'description' => $this->description,
            'security_stock' => $this->securityStock,
            'is_visible' => $this->isVisible,
            'old_price_amount' => $this->old_price_amount,
            'price_amount' => $this->price_amount,
            'cost_amount' => $this->cost_amount,
            'type' => $this->type,
            'requires_shipping' => $this->requiresShipping,
            'backorder' => $this->backorder,
            'published_at' => $this->publishedAt ?? now(),
            'seo_title' => $this->seoTitle,
            'seo_description' => str_limit($this->seoDescription, 157),
            'weight_value' => $this->weightValue ?? null,
            'weight_unit' => $this->weightUnit,
            'height_value' => $this->heightValue ?? null,
            'height_unit' => $this->heightUnit,
            'width_value' => $this->widthValue ?? null,
            'width_unit' => $this->widthUnit,
            'volume_value' => $this->volumeValue ?? null,
            'volume_unit' => $this->volumeUnit,
            'brand_id' => $this->brand_id ?? null,
        ]);

        if (collect($this->files)->isNotEmpty()) {
            collect($this->files)->each(
                // @phpstan-ignore-next-line
                fn ($file) => $product->addMedia($file)->toMediaCollection(config('shopper.core.storage.collection_name'))
            );
        }

        if (collect($this->category_ids)->isNotEmpty()) {
            // @phpstan-ignore-next-line
            $product->categories()->attach($this->category_ids);
        }

        if (collect($this->collection_ids)->isNotEmpty()) {
            // @phpstan-ignore-next-line
            $product->collections()->attach($this->collection_ids);
        }

        // @phpstan-ignore-next-line
        $product->channels()->attach($this->defaultChannel->id);

        if ($this->quantity && count($this->quantity) > 0) {
            foreach ($this->quantity as $inventory => $value) {
                // @phpstan-ignore-next-line
                $product->mutateStock(
                    $inventory,
                    (int) $value,
                    [
                        'event' => __('shopper::pages/products.inventory.initial'),
                        'old_quantity' => $value,
                    ]
                );
            }
        }

        session()->flash('success', __('shopper::pages/products.notifications.create'));

        $this->redirectRoute('shopper.products.index');
    }

    public function render(): View
    {
        return view('shopper::livewire.products.create', [
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
            'collections' => (new CollectionRepository())
                ->with('media')
                ->get(['name', 'id']),
            'inventories' => Inventory::query()->get(['name', 'id']),
            'currency' => shopper_currency(),
            'barcodeImage' => $this->barcode
                ? DNS1DFacade::getBarcodeHTML($this->barcode, config('shopper.core.barcode_type')) // @phpstan-ignore-line
                : null,
        ]);
    }
}
