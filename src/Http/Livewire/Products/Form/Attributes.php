<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Product\Attribute;
use Shopper\Framework\Models\Shop\Product\ProductAttribute;
use Shopper\Framework\Models\Shop\Product\ProductAttributeValue;
use WireUi\Traits\Actions;

class Attributes extends Component
{
    use Actions;

    public Model $product;
    public int $productId;
    public Collection $attributes;
    public Collection $productAttributes;

    protected $listeners = ['onProductAttributeAdded'];

    public function mount($product)
    {
        $this->product = $product;
        $this->productId = $product->id;
        $this->getProductAttributes();
        $this->getAttributes();
    }

    public function onProductAttributeAdded()
    {
        $this->getProductAttributes();
        $this->getAttributes();
    }

    public function removeProductAttributeValue(int $id): void
    {
        ProductAttributeValue::find($id)->delete();

        $this->notification()->success(__('Attribute value removed'), __('You have successfully removed the value of this attribute!'));

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

        $this->getProductAttributes();
        $this->getAttributes();

        $this->notification()->success(__('Attribute removed'), __('You have successfully removed this attribute to product!'));
    }

    public function render()
    {
        return view('shopper::livewire.products.forms.form-attributes');
    }

    private function attributeModelValue(string $type): string
    {
        if (in_array($type, ['text', 'number', 'richtext', 'select', 'datepicker', 'radio'])) {
            return 'single';
        } else {
            return 'multiple';
        }
    }

    private function getProductAttributes()
    {
        $this->productAttributes = ProductAttribute::query()
            ->with('values')
            ->where('product_id', $this->product->id)
            ->get()
            ->map(function ($pa) {
                $pa['type'] = $pa->attribute->type;
                $pa['model'] = $this->attributeModelValue($pa->attribute->type);

                return $pa;
            });
    }

    private function getAttributes()
    {
        $this->attributes = Attribute::query()
            ->whereNotIn('id', $this->productAttributes->pluck('attribute_id')->toArray())
            ->where('is_enabled', true)
            ->get();
    }
}
