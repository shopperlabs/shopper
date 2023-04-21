<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Attributes;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Product\Attribute;
use Shopper\Framework\Models\Shop\Product\AttributeValue;

class Values extends Component
{
    public Attribute $attribute;

    public Collection $values;

    public string $type = 'select';

    protected $listeners = ['updateValues'];

    public function mount(Attribute $attribute): void
    {
        $this->attribute = $attribute;
        $this->values = $attribute->values;
        $this->type = $attribute->type;
    }

    public function updateValues(): void
    {
        $this->values = AttributeValue::query()
            ->where('attribute_id', $this->attribute->id)
            ->get();
    }

    public function removeValue(int $id): void
    {
        AttributeValue::query()->find($id)->delete();

        $this->emitSelf('updateValues');

        Notification::make()
            ->title(__('shopper::layout.status.delete'))
            ->body(__('Your value have been correctly removed'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.attributes.values');
    }
}
