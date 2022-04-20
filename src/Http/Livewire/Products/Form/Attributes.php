<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Product\ProductAttribute;
use Shopper\Framework\Models\Shop\Product\ProductAttributeValue;
use Shopper\Framework\Traits\WithAttributes;
use WireUi\Traits\Actions;

class Attributes extends Component
{
    use Actions, WithAttributes;

    public Model $product;
    public int $productId;
    public Collection $attributes;
    public Collection $productAttributes;

    protected $listeners = ['onProductAttributeAdded'];

    public function mount($product)
    {
        $this->product = $product;
        $this->productId = $product->id;

        $this->onProductAttributeAdded();
    }

    public function onProductAttributeAdded()
    {
        $this->productAttributes = $this->getProductAttributes();
        $this->attributes = $this->getAttributes();
    }

    public function removeProductAttributeValue(int $id): void
    {
        ProductAttributeValue::find($id)->delete();

        $this->notification()->success(
            __('shopper::pages/products.attributes.session.delete_value'),
            __('shopper::pages/products.attributes.session.delete_value_message')
        );

        $this->emitSelf('onProductAttributeAdded');
    }

    /**
     * Remove Attribute to product.
     *
     * @throws Exception
     */
    public function removeProductAttribute(int $id)
    {
        ProductAttribute::query()->find($id)->delete();

        $this->productAttributes = $this->getProductAttributes();
        $this->attributes = $this->getAttributes();

        $this->notification()->success(
            __('shopper::pages/products.attributes.session.delete'),
            __('shopper::pages/products.attributes.session.delete_message')
        );
    }

    public function render()
    {
        return view('shopper::livewire.products.forms.form-attributes');
    }
}
