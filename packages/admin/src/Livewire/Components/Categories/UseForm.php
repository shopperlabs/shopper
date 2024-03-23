<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Categories;

use Illuminate\Database\Eloquent\Model;

trait UseForm
{
    public ?int $categoryId = null;

    public string $name = '';

    public ?int $parent_id = null;

    public ?string $description = null;

    public bool $is_enabled = true;

    public ?string $fileUrl = null;

    public $parent;

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
            'name' => 'required|max:150',
        ];
    }

    public function save(Model|string $model): mixed
    {
        $this->validate($this->rules());

        $values = [
            'name' => $this->name,
            'slug' => $this->parent ? $this->parent->slug . '-' . $this->name : $this->name,
            'parent_id' => $this->parent_id,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
            'seo_title' => $this->seoTitle,
            'seo_description' => str_limit($this->seoDescription, 157),
        ];

        return $this->categoryId ? $model->update($values) : $model::query()->create($values);
    }
}
