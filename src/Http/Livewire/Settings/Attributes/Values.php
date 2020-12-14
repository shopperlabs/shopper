<?php

namespace Shopper\Framework\Http\Livewire\Settings\Attributes;

use Illuminate\Validation\Rule;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\Shop\Product\Attribute;
use Shopper\Framework\Models\Shop\Product\AttributeValue;

class Values extends AbstractBaseComponent
{
    /**
     * Components Listeners.
     *
     * @var string[]
     */
    protected $listeners = ['updateValues'];

    /**
     * Current updated attribute.
     *
     * @var Attribute
     */
    public Attribute $attribute;

    /**
     * Attribute id for validation.
     *
     * @var int
     */
    public $attributeId;

    /**
     * Value search value.
     *
     * @var string
     */
    public $search;

    /**
     * Attribute values.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $values;

    /**
     * Value id to update on modal.
     *
     * @var int
     */
    public $valueId;

    /**
     * Attribute type.
     *
     * @var string
     */
    public $type = 'select';

    /**
     * Attribute value.
     *
     * @var string
     */
    public $value;

    /**
     * Attribute value key.
     *
     * @var string
     */
    public $key;

    /**
     * Component Mount new instance.
     *
     * @param  Attribute  $attribute
     * @return void
     */
    public function mount(Attribute $attribute)
    {
        $this->attribute = $attribute;
        $this->attributeId = $attribute->id;
        $this->values = $attribute->values;
        $this->type = $attribute->type;
    }

    /**
     * Update Values collections.
     *
     * @return void
     */
    public function updateValues()
    {
        $this->values = AttributeValue::query()
            ->where('attribute_id', $this->attributeId)
            ->get();
    }

    /**
     * Add new entry to the database.
     *
     * @return void
     */
    public function store()
    {
        $this->validate($this->rules());

        $this->attribute->values()->create([
           'value' => $this->value,
            'key'  => $this->key,
        ]);

        $this->dispatchBrowserEvent('modal-close');
        $this->emitSelf('updateValues');

        $this->notify([
            'title' => __('Added!'),
            'message' => __("New value added for :attribute", ['attribute' => $this->attribute->name]),
        ]);

        $this->value = '';
        $this->key = '';
        $this->valueId = null;
    }

    /**
     * Display edition modal with full filled data.
     *
     * @param  int  $id
     * @return void
     */
    public function modalEdit(int $id)
    {
        $value = AttributeValue::query()->find($id);

        $this->valueId = $id;
        $this->value = $value->value;
        $this->key = $value->key;

        $this->dispatchBrowserEvent('modal-open');
    }

    /**
     * Update the current Payment on the modal.
     *
     * @return void
     */
    public function updateValue()
    {
        $this->validate([
            'value' => 'sometimes|required',
            'key' => [
                'required',
                Rule::unique(shopper_table('attribute_values'), 'key')->ignore($this->valueId),
            ]
        ]);

        AttributeValue::query()
            ->find($this->valueId)
            ->update(['value' => $this->value, 'key' => $this->key]);

        $this->closeModal();
        $this->emitSelf('updateValues');

        $this->notify([
            'title' => __("Update"),
            'message' => __("Your value have been correctly updated."),
        ]);
    }

    /**
     * Removed item from the storage.
     *
     * @param  int  $id
     * @throws \Exception
     */
    public function removeValue(int $id)
    {
        AttributeValue::query()->find($id)->delete();

        $this->emitSelf('updateValues');

        $this->notify([
            'title' => __("Deleted"),
            'message' => __("Your value have been correctly removed."),
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

        $this->valueId = null;
        $this->value = '';
        $this->key = '';
    }


    /**
     * Component validation rules.
     *
     * @return string[]
     */
    protected function rules()
    {
        return [
            'value' => 'required|max:50',
            'key' => 'required|unique:'. shopper_table('attribute_values'),
        ];
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.attributes.values');
    }
}
