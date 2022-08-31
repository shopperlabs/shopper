<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\Shop\Product\AttributeValue;
use WireUi\Traits\Actions;

class UpdateValue extends ModalComponent
{
    use Actions;

    public string $name;

    public string $type = 'select';

    public int $valueId;

    public string $value;

    public string $key;

    public function mount(string $name, string $type, int $id)
    {
        $value = AttributeValue::query()->find($id);

        $this->valueId = $id;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value->value;
        $this->key = $value->key;
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function save()
    {
        $this->validate($this->rules());

        AttributeValue::query()
            ->find($this->valueId)
            ->update(['value' => $this->value, 'key' => $this->key]);

        $this->emit('updateValues');

        $this->notification()->success(__('Updated'), __('Your value have been correctly updated.'));

        $this->closeModal();
    }

    public function rules(): array
    {
        return [
            'value' => 'required|max:50',
            'key' => [
                'required',
                Rule::unique(shopper_table('attribute_values'))->ignore($this->valueId),
            ],
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.update-value');
    }
}
