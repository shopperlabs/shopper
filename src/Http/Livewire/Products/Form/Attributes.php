<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Product\Attribute;
use Shopper\Framework\Models\Shop\Product\ProductAttribute;
use Shopper\Framework\Models\Shop\Product\ProductAttributeValue;

class Attributes extends Component
{
    /**
     * Product Model.
     *
     * @var Model
     */
    public $product;

    /**
     * Product Id.
     *
     * @var int
     */
    public $productId;

    /**
     * List of available attributes from the storage.
     *
     * @var Collection
     */
    public $attributes;

    /**
     * List of attributes for the product.
     *
     * @var Collection
     */
    public $productAttributes;

    /**
     * Attribute Id.
     *
     * @var int
     */
    public $attribute_id;

    /**
     * Attribute type
     *
     * @var string
     */
    public $type = 'text';

    /**
     * Values of the selected attribute if is the typ of array.
     *
     * @var Collection
     */
    public $values;

    /**
     * Product attribute value added.
     *
     * @var mixed
     */
    public $value;

    /**
     * Product multi array value
     *
     * @var array
     */
    public $multipleValues = [];

    /**
     * Launch Attribute modale.
     *
     * @var bool
     */
    public $launchModale = false;

    /**
     * Component Mount method.
     *
     * @param  mixed  $product
     * @return void
     */
    public function mount($product)
    {
        $this->product = $product;
        $this->productId = $product->id;
        $this->getProductAttributes();
        $this->getAttributes();
    }

    /**
     * Update type field of the selected attribute.
     *
     * @param  string  $value
     */
    public function updatedAttributeId(string $value)
    {
        if ($value === "0") {
            return;
        }

        $attribute = Attribute::query()->with('values')->find($value);
        $this->type = $attribute->type;
        $this->value = '';

        if ($attribute->values->isNotEmpty()) {
            $this->values = $attribute->values;
        }
    }

    /**
     * Add attribute with value on the current product.
     *
     * @return void
     */
    public function addAttribute()
    {
        if ($this->type === 'checkbox' || $this->type === 'checkbox_list') {
            $this->validate(['multipleValues' => 'required|array']);
        } else {
            $this->validate(['value' => 'required', 'attribute_id' => 'required|int']);
        }

        $productAttribute = ProductAttribute::query()->create([
            'product_id' => $this->productId,
            'attribute_id' => $this->attribute_id,
        ]);

        if ($this->type === 'checkbox' || $this->type === 'checkbox_list') {
            foreach ($this->multipleValues as $checkboxValue) {
                ProductAttributeValue::query()->create([
                    'attribute_value_id'    => $checkboxValue,
                    'product_attribute_id'  => $productAttribute->id,
                ]);
            }
        } else {
            ProductAttributeValue::query()->create([
                'attribute_value_id'    => in_array($this->type, Attribute::fieldsWithStringValues())
                    ? null
                    : $this->value,
                'product_attribute_id'  => $productAttribute->id,
                'product_custom_value'  => in_array($this->type, Attribute::fieldsWithStringValues())
                    ? $this->value
                    : null,
            ]);
        }

        $this->closeModal();

        $this->notify([
            'title' => __("Attribute Added"),
            'message' => __("You have successfully added an attribute to this product."),
        ]);
    }

    public function updateProductAttribute(int $id)
    {
        dd($id);
    }

    /**
     * Remove Attribute to product.
     *
     * @param  int  $id
     * @throws \Exception
     */
    public function removeProductAttribute(int $id)
    {
        ProductAttribute::query()->find($id)->delete();

        $this->getProductAttributes();
        $this->getAttributes();

        $this->notify([
            'title' => __("Attribute Removed"),
            'message' => __("You have successfully removed this attribute to product."),
        ]);
    }

    /**
     * Launch modale to remove product.
     *
     * @return void
     */
    public function openModale()
    {
        $this->launchModale = true;
    }

    /**
     * Close Modal.
     *
     * @return void
     */
    public function closeModal()
    {
        $this->getProductAttributes();
        $this->getAttributes();

        $this->launchModale = false;
        $this->resetErrorBag();

        $this->value = null;
        $this->type = 'text';
        $this->multipleValues = [];
        $this->attribute_id = '0';
    }

    /**
     * Get Product Attributes lists.
     *
     * @return void
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
     *
     * @return void
     */
    private function getAttributes()
    {
        $this->attributes = Attribute::query()
            ->whereNotIn('id', $this->productAttributes->pluck('attribute_id')->toArray())
            ->where('is_enabled', true)
            ->get();
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.products.forms.form-attributes');
    }
}
