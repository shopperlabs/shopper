<?php

namespace Shopper\Framework\Http\Livewire\Settings\Attributes;

use Livewire\Component;
use Shopper\Framework\Models\Shop\Product\Attribute;

class Values extends Component
{
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
        $this->attribute = $attribute->load('values');
        $this->attributeId = $attribute->id;
        $this->values = $this->attribute->values;
        $this->type = $attribute->type;
    }

    /**
     * Add new entry to the database.
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'value' => 'required|max:50',
            'key' => 'required|unique:'. shopper_table('attribute_values'),
        ]);
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
