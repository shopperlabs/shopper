<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Milon\Barcode\Facades\DNS1DFacade;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\Shop\Channel;
use Shopper\Framework\Models\Shop\Inventory\Inventory;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Traits\WithChoicesBrands;
use Shopper\Framework\Traits\WithSeoAttributes;

class Create extends AbstractBaseComponent
{
    use WithAttributes, WithSeoAttributes, WithChoicesBrands;

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

    public function mount()
    {
        $this->defaultChannel = Channel::query()->where('slug', 'web-store')->first();
    }

    public function onTrixValueUpdate($value)
    {
        $this->description = $value;
    }

    public function onFilesUpdated($files)
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

    public function store()
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
                fn ($file) => $product->addMedia($file)->toMediaCollection(config('shopper.system.storage.disks.uploads'))
            );
        }

        if (collect($this->category_ids)->isNotEmpty()) {
            $product->categories()->attach($this->category_ids);
        }

        if (collect($this->collection_ids)->isNotEmpty()) {
            $product->collections()->attach($this->collection_ids);
        }

        $product->channels()->attach($this->defaultChannel->id);

        if ($this->quantity && count($this->quantity) > 0) {
            foreach ($this->quantity as $inventory => $value) {
                $product->mutateStock(
                    $inventory,
                    $value,
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

    public function render()
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
            'collections' => (new CollectionRepository())->with('media')->get(['name', 'id']),
            'inventories' => Inventory::query()->get(['name', 'id']),
            'currency' => shopper_currency(),
            'barcodeImage' => $this->barcode
                ? DNS1DFacade::getBarcodeHTML($this->barcode, config('shopper.system.barcode_type'))
                : null,
        ]);
    }
}
