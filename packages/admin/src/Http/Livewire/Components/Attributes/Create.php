<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Attributes;

use Illuminate\Contracts\View\View;
use Shopper\Core\Models\Attribute;
use Shopper\Http\Livewire\AbstractBaseComponent;

class Create extends AbstractBaseComponent
{
    public string $name = '';

    public string $slug = '';

    public string $type = 'text';

    public ?string $description = null;

    public bool $isEnabled = false;

    public bool $isSearchable = false;

    public bool $isFilterable = false;

    public function updatedName(string $value): void
    {
        $this->slug = str_slug($value);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:75',
            'slug' => 'required|unique:' . shopper_table('attributes'),
            'type' => 'required',
        ];
    }

    public function store(): void
    {
        $this->validate($this->rules());

        $attribute = Attribute::query()->create([
            'name' => $this->name,
            'slug' => str_slug($this->slug, '-'),
            'type' => $this->type,
            'description' => $this->description,
            'is_enabled' => $this->isEnabled,
            'is_searchable' => $this->isSearchable,
            'is_filterable' => $this->isFilterable,
        ]);

        session()->flash('success', __('Attribute successfully added'));

        $this->redirectRoute('shopper.settings.attributes.edit', $attribute);
    }

    public function render(): View
    {
        return view('shopper::livewire.attributes.create', [
            'fields' => Attribute::typesFields(),
        ]);
    }
}
