<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Exception;
use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Shopper\Framework\Models\Shop\Product\Attribute;
use Shopper\Framework\Models\Shop\Product\ProductAttribute;

class Attributes extends Component
{
    /**
     * Product Model.
     *
     * @var Model
     */
    public $product;
    public int $productId;
    public Collection $attributes;
    public Collection $productAttributes;

    protected $listeners = ['onProductAttributeAdded' => 'render'];

    public function mount($product)
    {
        $this->product = $product;
        $this->productId = $product->id;
        $this->getProductAttributes();
        $this->getAttributes();
    }

    public function updateProductAttribute(int $id)
    {
        // ToDo
    }

    /**
     * Remove Attribute to product.
     *
     * @throws Exception
     */
    public function removeProductAttribute(int $id)
    {
        ProductAttribute::query()->find($id)->delete();

        $this->getProductAttributes();
        $this->getAttributes();

        $this->notify([
            'title' => __('Attribute Removed'),
            'message' => __('You have successfully removed this attribute to product.'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.products.forms.form-attributes');
    }

    /**
     * Get Product Attributes lists.
     */
    private function getProductAttributes()
    {
        $this->productAttributes = ProductAttribute::query()
            ->with('values')
            ->where('product_id', $this->product->id)
            ->get()
            ->map(function ($pa) {
                $pa['type'] = $pa->attribute->type;

                return $pa;
            });
    }

    /**
     * Get Attributes lists not used by the product.
     */
    private function getAttributes()
    {
        $this->attributes = Attribute::query()
            ->whereNotIn('id', $this->productAttributes->pluck('attribute_id')->toArray())
            ->where('is_enabled', true)
            ->get();
    }
}
