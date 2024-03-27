<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Attributes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

trait UseForm
{
    public ?int $attributeId = null;

    public string $name = '';

    public string $slug = '';

    public string $type = 'text';

    public ?string $icon = null;

    public ?string $description = null;

    public bool $isEnabled = false;

    public bool $isSearchable = false;

    public bool $isFilterable = false;

    public function rules(): array
    {
        return [
            'name' => 'required|max:75',
            'slug' => array_merge([
                'required',
            ], $this->attributeId ? [
                Rule::unique(shopper_table('attributes'), 'slug')->ignore($this->attributeId),
            ] : ['unique:' . shopper_table('attributes')]),
            'type' => 'required',
        ];
    }

    public function updatedName(string $value): void
    {
        $this->slug = str_slug($value);
    }

    public function selectedIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    public function save(Model | string $model): mixed
    {
        $this->validate($this->rules());

        $values = [
            'name' => $this->name,
            'slug' => $model->name ?? $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'is_enabled' => $this->isEnabled,
            'is_searchable' => $this->isSearchable,
            'is_filterable' => $this->isFilterable,
            'icon' => $this->icon,
        ];

        return $this->attributeId ? $model->update($values) : $model::query()->create($values);
    }
}
