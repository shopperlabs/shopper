<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Exception;
use function in_array;
use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
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
     */
    public function updatedAttributeId(string $value)
    {
        if ($value === '0') {
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
            'title' => __('Attribute Added'),
            'message' => __('You have successfully added an attribute to this product.'),
        ]);
    }

    public function updateProductAttribute(int $id)
    {
        dd($id);
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

    /**
     * Launch modale to remove product.
     */
    public function openModale()
    {
        $this->launchModale = true;
    }

    /**
     * Close Modal.
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
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
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
