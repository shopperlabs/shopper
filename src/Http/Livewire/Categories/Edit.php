<?php

namespace Shopper\Framework\Http\Livewire\Categories;

use Illuminate\Validation\Rule;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

class Edit extends AbstractBaseComponent
{
   use WithSeoAttributes;

    public $category;
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
        $this->seoTitle = $category->seo_title;
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

    public function updatedSelectedCategory($choice)
    {
        if (count($choice) > 0 && $choice['value'] !== "0") {
            $this->parent_id = (int) $choice['value'];
            $this->parent = (new CategoryRepository())->getById($this->parent_id);
        } else {
            $this->parent_id = null;
            $this->parent = null;
        }
    }

    /**
     * Define is the current action is create or update for the SEO Trait.
     *
     * @return false
     */
    public function isUpdate(): bool
    {
        return true;
    }

    public function store()
    {
        $this->validate($this->rules());

        $this->category->update([
            'name' => $this->name,
            'slug' => $this->parent ? $this->parent->slug. '-' .$this->name : $this->name,
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
        return [
            'name' => [
                'sometimes',
                'required',
                'max:150',
                Rule::unique(shopper_table('categories'), 'name')->ignore($this->categoryId),
            ],
        ];
    }

    public function render()
    {
        return view('shopper::livewire.categories.edit', [
            'categories' => (new CategoryRepository())
                ->makeModel()
                ->scopes('enabled')
                ->select('name', 'id')
                ->get()
                ->except($this->category->id),
        ]);
    }
}
