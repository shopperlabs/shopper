<?php

namespace Shopper\Framework\Http\Livewire\Settings\Attributes;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Product\Attribute;
use Shopper\Framework\Models\Shop\Product\AttributeValue;

class Values extends Component
{
    protected $listeners = ['updateValues'];

    public Attribute $attribute;
    public ?string $search;
    public Collection $values;
    public ?int $valueId;
    public string $type = 'select';

    public function mount(Attribute $attribute)
    {
        $this->attribute = $attribute;
        $this->values = $attribute->values;
        $this->type = $attribute->type;
    }

    public function updateValues()
    {
        $this->values = AttributeValue::query()
            ->where('attribute_id', $this->attribute->id)
            ->get();
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

        $this->createModale = true;
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
            'title' => __('Deleted'),
            'message' => __('Your value have been correctly removed.'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.settings.attributes.values');
    }
}
