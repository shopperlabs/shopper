<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
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
     * Component Mount method.
     *
     * @return void
     */
    public function mount($product)
    {
        $this->product = $product;
        $this->productId = $product->id;
        $this->attributes = Attribute::query()->where('is_enabled', true)->get();
        $this->productAttributes = ProductAttribute::query()
            ->with('values')
            ->where('product_id', $product->id)
            ->get();
    }

    /**
     * Update type field of the selected attribute.
     *
     * @param  string  $value
     */
    public function updatedAttributeId($value)
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
            $this->validate(['value' => 'required']);
        }

        $productAttribute = ProductAttribute::query()->create([
            'product_id' => $this->productId,
            'attribute_id' => (int) $this->attribute_id,
        ]);

        if ($this->type === 'checkbox' || $this->type === 'checkbox_list') {
            foreach ($this->multipleValues as $checkboxValue) {
                DB::table(shopper_table('attribute_value_product_attribute'))->insert([
                    'attribute_value_id'    => $checkboxValue,
                    'product_attribute_id'  => $productAttribute->id,
                ]);
            }
        } else {
            DB::table(shopper_table('attribute_value_product_attribute'))->insert([
                'attribute_value_id'    => in_array($this->type, ['text', 'number', 'richtext', 'markdown'])
                    ? null
                    : $this->value,
                'product_attribute_id'  => $productAttribute->id,
                'product_custom_value'  => in_array($this->type, ['text', 'number', 'richtext', 'markdown'])
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

    /**
     * Close Modal.
     *
     * @return void
     */
    public function closeModal()
    {
        $this->dispatchBrowserEvent('modal-close');
        $this->resetErrorBag();

        $this->value = null;
        $this->type = 'text';
        $this->multipleValues = [];
        $this->attribute_id = '0';
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('shopper::livewire.products.forms.form-attributes');
    }
}
