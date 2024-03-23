<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Categories;

use Illuminate\Contracts\View\View;
use Shopper\Contracts\HasForm;
use Shopper\Core\Repositories\Store\CategoryRepository;
use Shopper\Core\Traits\Attributes\WithChoicesCategories;
use Shopper\Core\Traits\Attributes\WithSeoAttributes;
use Shopper\Livewire\AbstractBaseComponent;

class Edit extends AbstractBaseComponent implements HasForm
{
    use UseForm;
    use WithChoicesCategories;
    use WithSeoAttributes;

    public $category;

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'shopper:fileUpdated' => 'onFileUpdate',
    ];

    public function mount($category): void
    {
        $this->category = $category;
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->parent_id = $category->parent_id;
        $this->description = $category->description;
        $this->is_enabled = $category->is_enabled;
        $this->updateSeo = true;
        $this->seoTitle = $category->seo_title ?? $category->name;
        $this->seoDescription = $category->seo_description;
        $this->selectedCategory = $category->parent_id ? [$category->parent_id] : [];
        $this->parent = $category->parent_id ? $category->parent : null;
    }

    public function isUpdate(): bool
    {
        return true;
    }

    public function store(): void
    {
        $this->save($this->category);

        if ($this->fileUrl) {
            $this->category->addMedia($this->fileUrl)->toMediaCollection(config('shopper.core.storage.collection_name'));
        }

        session()->flash('success', __('shopper::notifications.actions.update', ['item' => __('shopper::words.category')]));

        $this->redirectRoute('shopper.categories.index');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.categories.edit', [
            'categories' => (new CategoryRepository())
                ->makeModel()
                ->scopes('enabled')
                ->tree()
                ->orderBy('name')
                ->get()
                ->toTree(),
        ]);
    }
}
