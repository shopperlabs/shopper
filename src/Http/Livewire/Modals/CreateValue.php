<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
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

    public function mount(int $attributeId): void
    {
        $this->attribute = $attribute = Attribute::find($attributeId);
        $this->type = $attribute->type;
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function save(): void
    {
        $this->validate($this->rules());

        $this->attribute->values()->create([
            'value' => $this->value,
            'key' => $this->key,
        ]);

        $this->emit('updateValues');

        $this->notification()->success(
            __('shopper::layout.status.added'),
            __('New value added for :name', ['name' => $this->attribute->name])
        );

        $this->closeModal();
    }

    public function rules(): array
    {
        return [
            'value' => 'required|max:50',
            'key' => 'required|unique:' . shopper_table('attribute_values'),
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.create-value');
    }
}
