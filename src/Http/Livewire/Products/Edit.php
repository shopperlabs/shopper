<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Livewire\WithFileUploads;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Traits\WithUploadProcess;

class Edit extends AbstractBaseComponent
{
    use WithFileUploads,
        WithUploadProcess,
        WithAttributes,
        WithSeoAttributes;

    /**
     * Product Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $product;

    /**
     * Product Id.
     *
     * @var int
     */
    public $productId;

    /**
     * Component Mount method.
     *
     * @return void
     */
    public function mount($product)
    {
        $this->product = $product;
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->brand_id = $product->brand_id;
        $this->barcode = $product->barcode;
        $this->description = $product->description;
        $this->securityStock = $product->security_stock;
        $this->isVisible = $product->is_visible;
        $this->price_amount = $product->price_amount;
        $this->old_price_amount = $product->old_price_amount;
        $this->cost_amount = $product->cost_amount;
        $this->type = $product->type;
        $this->requiredShipping = $product->requires_shipping;
        $this->publishedAt = $product->published_at;
        $this->seoTitle = $product->seo_title;
        $this->seoDescription = $product->seo_description;
        $this->weightValue = $product->weight_value;
        $this->weightUnit = $product->weight_unit;
        $this->heightValue = $product->height_value;
        $this->heightUnit = $product->height_unit;
        $this->widthValue = $product->width_value;
        $this->widthUnit = $product->width_unit;
        $this->volumeValue = $product->volume_value;
        $this->volumeUnit = $product->volume_unit;
    }

    /**
     * Component validation rules.
     *
     * @return string[]
     */
    protected function rules()
    {
        return [

        ];
    }

    /**
     * Store/Update a entry to the storage.
     *
     * @return void
     */
    public function store()
    {

    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
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
                ->select('name', 'id')
                ->get(),
            'collections' => (new CollectionRepository())->get(['name', 'id'])
        ]);
    }
}
