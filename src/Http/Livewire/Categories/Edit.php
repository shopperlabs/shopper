<?php

namespace Shopper\Framework\Http\Livewire\Categories;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Traits\WithChoicesCategories;
use Shopper\Framework\Traits\WithSeoAttributes;

class Edit extends AbstractBaseComponent
{
    use WithChoicesCategories;
    use WithSeoAttributes;

    public Model $category;

    public int $categoryId;

    public string $name = '';

    public ?int $parent_id = null;

    public ?string $description = null;

    public bool $is_enabled = false;

    public ?string $fileUrl = null;

    public $selectedCategory = [];

    public $parent;

    public $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'shopper:fileUpdated' => 'onFileUpdate',
    ];

    public function mount($category)
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
        $this->selectedCategory = $category->parent_id ? $this->selectedCategory['value'] = $category->parent_id : [];
        $this->parent = $category->parent_id ? $category->parent : null;
    }

    public function onTrixValueUpdate($value)
    {
        $this->description = $value;
    }

    public function onFileUpdate($file)
    {
        $this->fileUrl = $file;
    }

    public function isUpdate(): bool
    {
        return true;
    }

    public function store()
    {
        $this->validate($this->rules());

        $this->category->update([
            'name' => $this->name,
            'slug' => $this->parent ? $this->parent->slug . '-' . $this->name : $this->name,
            'parent_id' => $this->parent_id,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
            'seo_title' => $this->seoTitle,
            'seo_description' => str_limit($this->seoDescription, 157),
        ]);

        if ($this->fileUrl) {
            $this->category->addMedia($this->fileUrl)->toMediaCollection(config('shopper.system.storage.disks.uploads'));
        }

        session()->flash('success', __('Category successfully updated!'));

        $this->redirectRoute('shopper.categories.index');
    }

    public function rules(): array
    {
        return ['name' => 'sometimes|required|max:150'];
    }

    public function render(): View
    {
        return view('shopper::livewire.categories.edit', [
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
