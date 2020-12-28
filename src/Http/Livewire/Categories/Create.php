<?php

namespace Shopper\Framework\Http\Livewire\Categories;

use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Traits\WithUploadProcess;

class Create extends AbstractBaseComponent
{
    use WithFileUploads, WithUploadProcess;

    /**
     * Category name.
     *
     * @var string
     */
    public $name = '';

    /**
     * Category slug for custom url.
     *
     * @var string
     */
    public $slug;

    /**
     * Category parent_id.
     *
     * @var string
     */
    public $parent_id;

    /**
     * Category sample description.
     *
     * @var string
     */
    public $description;

    /**
     * Indicates if category is being enabled.
     *
     * @var bool
     */
    public $is_enabled = false;

    /**
     * Save new entry to the database.
     *
     * @return void
     */
    public function store()
    {
        $this->validate($this->rules());

        $category = (new CategoryRepository())->create([
            'name' => $this->name,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
        ]);

        if ($this->file) {
            $this->uploadFile(config('shopper.system.models.category'), $category->id);
        }

        session()->flash('success', __("Category successfully added!"));
        $this->redirectRoute('shopper.categories.index');
    }

    /**
     * Update slug value when name if updated.
     *
     * @param  string  $value
     * @return void
     */
    public function updatedName(string $value)
    {
        $this->slug = str_slug($value, '-');
    }

    /**
     * Component validation rules.
     *
     * @return string[]
     */
    public function rules()
    {
        return [
            'name' => 'required|max:150|unique:'.shopper_table('categories'),
            'slug' => 'required|unique:'.shopper_table('categories'),
            'file' => 'nullable|image|max:1024',
        ];
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     * @throws \Shopper\Framework\Exceptions\GeneralException
     */
    public function render()
    {
        return view('shopper::livewire.categories.create', [
            'categories' => (new CategoryRepository())
                ->makeModel()
                ->scopes('enabled')
                ->select('name', 'id')
                ->get()
        ]);
    }
}
