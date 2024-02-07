<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Brands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

trait UseForm
{
    public ?int $brandId = null;

    public string $name = '';

    public ?string $website = null;

    public ?string $description = null;

    public bool $is_enabled = true;

    public ?string $fileUrl = null;

    public $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    public function onTrixValueUpdate(string $value): void
    {
        $this->description = $value;
    }

    public function onFileUpdate($file): void
    {
        $this->fileUrl = $file;
    }

    public function rules(): array
    {
        return [
            'name' => array_merge([
                'required',
                'max:150',
            ], $this->brandId ? [
                Rule::unique(shopper_table('brands'), 'name')->ignore($this->brandId),
            ] : ['unique:' . shopper_table('brands')]),
        ];
    }

    public function save(Model|string $model): mixed
    {
        $this->validate($this->rules());

        $values = [
            'name' => $this->name,
            'slug' => $this->name,
            'website' => $this->website,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
            'seo_title' => $this->seoTitle,
            'seo_description' => str_limit($this->seoDescription, 157),
        ];

        return $this->brandId ? $model->update($values) : $model::query()->create($values);
    }
}
