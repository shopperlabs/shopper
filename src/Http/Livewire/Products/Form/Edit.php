<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Shopper\Framework\Events\Products\ProductUpdated;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Models\System\File;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
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
        $this->publishedAt = $product->published_at->format('Y-m-d');
        $this->publishedAtFormatted = $product->published_at->toRfc7231String();
        $this->collection_ids = $product->collections->pluck('id');
        $this->category_ids = $product->categories->pluck('id');
    }

    /**
     * Component validation rules.
     *
     * @return string[]
     */
    protected function rules()
    {
        return [
            'name' => 'required',
//            'sku'  => [
//                'nullable',
//                Rule::unique(shopper_table('products'), 'sku')->ignore($this->productId),
//            ],
//            'barcode'  => [
//                'nullable',
//                Rule::unique(shopper_table('products'), 'barcode')->ignore($this->productId),
//            ],
            'file' => 'nullable|image|max:1024',
            'brand_id' => 'integer|nullable|exists:'.shopper_table('brands').',id',
        ];
    }

    /**
     * Store/Update a entry to the storage.
     *
     * @return void
     */
    public function store()
    {
        $this->validate($this->rules());

        (new ProductRepository())->getById($this->product->id)->update([
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'is_visible' => $this->isVisible,
            'old_price_amount' => $this->old_price_amount,
            'price_amount' => $this->price_amount,
            'cost_amount' => $this->cost_amount,
            'published_at' => $this->publishedAt,
            'brand_id'  => $this->brand_id,
        ]);

        if ($this->file) {
            $this->uploadFile(config('shopper.system.models.product'), $this->productId);
        }

        if (count($this->category_ids) > 0) {
            $this->product->categories()->sync($this->category_ids);
        }

        if (count($this->collection_ids) > 0) {
            $this->product->collections()->sync($this->collection_ids);
        }

        event(new ProductUpdated($this->product));

        $this->emit('productHasUpdated', $this->productId);

        $this->notify([
            'title' => __("Updated"),
            'message' => __("Product successfully updated!"),
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
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
            'collections' => (new CollectionRepository())->get(['name', 'id'])
        ]);
    }
}
