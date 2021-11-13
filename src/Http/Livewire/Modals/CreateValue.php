<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\Shop\Product\Attribute;
use WireUi\Traits\Actions;

class CreateValue extends ModalComponent
{
    use Actions;

    public Attribute $attribute;
    public string $type = 'select';
    public string $value = '';
    public string $key = '';

    public function mount(int $attributeId)
    {
        $this->attribute = $attribute = Attribute::find($attributeId);
        $this->type = $attribute->type;
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function save()
    {
        $this->validate($this->rules());

        $this->attribute->values()->create([
            'value' => $this->value,
            'key' => $this->key,
        ]);

        $this->emit('updateValues');

        $this->notification()->success(__('Added'), __('New value added for :name', ['name' => $this->attribute->name]));

        $this->closeModal();
    }

    public function rules(): array
    {
        return [
            'value' => 'required|max:50',
            'key' => 'required|unique:' . shopper_table('attribute_values'),
        ];
    }

    public function render()
    {
        return view('shopper::livewire.modals.create-value');
    }
}
