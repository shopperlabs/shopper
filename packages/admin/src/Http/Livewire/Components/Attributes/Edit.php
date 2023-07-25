<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Attributes;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Shopper\Contracts\HasForm;
use Shopper\Core\Models\Attribute;
use Shopper\Http\Livewire\AbstractBaseComponent;

class Edit extends AbstractBaseComponent implements HasForm
{
    use UseForm;

    public Attribute $attribute;

    protected $listeners = ['selectedIcon'];

    public function mount(Attribute $attribute): void
    {
        $this->attribute = $attribute;
        $this->attributeId = $attribute->id;
        $this->name = $attribute->name;
        $this->slug = $attribute->slug;
        $this->type = $attribute->type;
        $this->icon = $attribute->icon;
        $this->description = $attribute->description;
        $this->isEnabled = $attribute->is_enabled;
        $this->isSearchable = $attribute->is_searchable;
        $this->isFilterable = $attribute->is_filterable;
    }

    public function store(): void
    {
        $this->save($this->attribute);

        Notification::make()
            ->title(__('shopper::components.tables.status.updated'))
            ->body(__('shopper::pages/attributes.notifications.updated'))
            ->success()
            ->send();
    }

    public function hasValues(): bool
    {
        return in_array($this->type, Attribute::fieldsWithValues());
    }

    public function render(): View
    {
        return view('shopper::livewire.components.attributes.edit', [
            'fields' => Attribute::typesFields(),
        ]);
    }
}
