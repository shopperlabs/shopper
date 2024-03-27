<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Attributes;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;

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
            ->body(__('shopper::pages/attributes.notifications.value_removed'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.attributes.values');
    }
}
