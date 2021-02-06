<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Illuminate\View\View;
use Livewire\WithFileUploads;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\Shop\Channel;
use Shopper\Framework\Models\Shop\Inventory\Inventory;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Traits\WithUploadProcess;

class Create extends AbstractBaseComponent
{
    use WithAttributes,
        WithFileUploads,
        WithSeoAttributes,
        WithUploadProcess;

    /**
     * Products images.
     *
     * @var array
     */
    public $files = [];

    /**
     * Image preview url.
     *
     * @var array
     */
    public $images = [];

    /**
     * Product custom event listeners.
     *
     * @var string[]
     */
    protected $listeners = ['productAdded'];

    /**
     * Default product stock quantity.
     *
     * @var mixed
     */
    public $quantity;

    /**
     * Product categories associate id.
     *
     * @var array
     */
    public $category_ids = [];

    /**
     * Product collections associate ids.
     *
     * @var array
     */
    public $collection_ids = [];

    /**
     * Product default published channel.
     *
     * @var Channel
     */
    public $defaultChannel;

    /**
     * Component Mount method.
     *
     * @return void
     */
    public function mount()
    {
        $this->defaultChannel = Channel::query()->where('slug', 'web-store')->first();
    }

    /**
     * Store a newly entry to the storage.
     *
     * @return void
     */
    public function store()
    {
        $this->validate($this->rules());

        $product = (new ProductRepository())->create([
            'name' => $this->name,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'description' => $this->description,
            'security_stock' => $this->securityStock,
            'is_visible' => $this->isVisible,
            'old_price_amount' => $this->old_price_amount,
            'price_amount' => $this->price_amount,
            'cost_amount' => $this->cost_amount,
            'type'  => $this->type,
            'requires_shipping' => $this->requiresShipping,
            'backorder' => $this->backorder,
            'published_at'  => $this->publishedAt ?? now(),
            'seo_title'     => $this->seoTitle,
            'seo_description' => str_limit($this->seoDescription, 157),
            'weight_value'  => $this->weightValue ?? null,
            'weight_unit'   => $this->weightUnit,
            'height_value'  => $this->heightValue ?? null,
            'height_unit'   => $this->heightUnit,
            'width_value'   => $this->widthValue ?? null,
            'width_unit'    => $this->widthUnit,
            'volume_value'  => $this->volumeValue ?? null,
            'volume_unit'   => $this->volumeUnit,
            'brand_id'  => $this->brand_id ?? null,
        ]);

        if ($this->file) {
            $this->uploadFile(config('shopper.system.models.product'), $product->id);
        }

        if (count($this->category_ids) > 0) {
            $product->categories()->sync($this->category_ids);
        }

        if (count($this->collection_ids) > 0) {
            $product->collections()->sync($this->collection_ids);
        }

        $product->channels()->attach($this->defaultChannel->id);

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

        session()->flash('success', __("Product successfully added!"));
        $this->redirectRoute('shopper.products.edit', $product);
    }

    /**
     * Remove file to the array.
     *
     * @param  int  $index
     * @return void
     */
    public function removeFile($index)
    {
        unset($this->files[$index]);
    }

    /**
     * Component validation rules.
     *
     * @return string[]
     */
    protected function rules()
    {
        return [
            'name'  => 'bail|required',
            'sku'  => 'nullable|unique:'.shopper_table('products'),
            'barcode'  => 'nullable|unique:'.shopper_table('products'),
            'files.*' => 'nullable|image|max:1024',
            'brand_id' => 'integer|nullable|exists:'.shopper_table('brands').',id',
        ];
    }

    /**
     * Render the component.
     *
     * @return View
     * @throws \Shopper\Framework\Exceptions\GeneralException
     */
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
                ->select('name', 'id')
                ->get(),
            'collections' => (new CollectionRepository())->get(['name', 'id']),
            'inventories' => Inventory::query()->get(['name', 'id']),
            'currency' => shopper_currency(),
        ]);
    }
}
