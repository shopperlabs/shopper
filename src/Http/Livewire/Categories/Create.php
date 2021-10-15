<?php

namespace Shopper\Framework\Http\Livewire\Categories;

use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

class Create extends AbstractBaseComponent
{
    use WithSeoAttributes;

    public string $name = '';
    public ?int $parent_id = null;
    public ?string $description = null;
    public bool $is_enabled = true;
    public array $selectedCategory = [];
    public ?string $fileUrl = null;
    public $parent;

    public $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'shopper:fileUpdated' => 'onFileUpdate',
    ];

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

    public function store(): void
    {
        $this->validate($this->rules());

        $category = (new CategoryRepository())->create([
            'name' => $this->name,
            'slug' => $this->parent ? $this->parent->slug. '-' .$this->name : $this->name,
            'parent_id' => $this->parent_id,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
            'seo_title' => $this->seoTitle,
            'seo_description' => $this->seoDescription,
        ]);

        if ($this->fileUrl) {
            $category->addMedia($this->fileUrl)->toMediaCollection(config('shopper.system.storage.disks.uploads'));
        }

        session()->flash('success', __('Category successfully added!'));

        $this->redirectRoute('shopper.categories.index');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:150',
        ];
    }

    public function render()
    {
        return view('shopper::livewire.categories.create', [
            'categories' => (new CategoryRepository())
                ->makeModel()
                ->with('parent')
                ->scopes('enabled')
                ->select('name', 'id', 'parent_id')
                ->get(),
        ]);
    }
}
