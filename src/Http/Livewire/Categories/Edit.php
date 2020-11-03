<?php

namespace Shopper\Framework\Http\Livewire\Categories;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Models\Shop\Product\Brand;
use Shopper\Framework\Models\System\File;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Traits\WithUploadProcess;

class Edit extends Component
{
    use WithFileUploads, WithUploadProcess;

    /**
     * Category Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $category;

    /**
     * Category Model id.
     *
     * @var int
     */
    public $categoryId;

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
     * Category parentId.
     *
     * @var string
     */
    public $parentId;

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
     * Component mounted action.
     *
     * @param  $category
     * @return void
     */
    public function mount($category)
    {
        $this->category = $category;
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->parentId = $category->parent_id;
        $this->description = $category->description;
        $this->is_enabled = $category->is_enabled;
    }

    /**
     * Update category record in the database.
     *
     * @return void
     */
    public function store()
    {
        $this->validate($this->rules());

        (new CategoryRepository())->getById($this->category->id)->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'parent_id' => $this->parentId,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
        ]);

        if ($this->file) {

            if ($this->category->files->isNotEmpty()) {
                foreach ($this->category->files as $file) {
                    Storage::disk(config('shopper.system.storage.disks.uploads'))->delete($file->disk_name);
                }
                File::query()->where('filetable_id', $this->categoryId)->delete();
            }

            $this->uploadFile(config('shopper.system.models.category'), $this->category->id);
        }

        session()->flash('success', __("Category successfully updated!"));
        $this->redirectRoute('shopper.categories.index');
    }

    /**
     * Real-time component validation.
     *
     * @param  string  $field
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    /**
     * Update slug value when name if updated.
     *
     * @param  string  $value
     * @return void
     */
    public function updatedName($value)
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
            'name' => [
                'sometimes',
                'required',
                'max:150',
                Rule::unique(shopper_table('categories'), 'name')->ignore($this->categoryId),
            ],
            'slug' => [
                'sometimes',
                'required',
                Rule::unique(shopper_table('categories'), 'slug')->ignore($this->categoryId),
            ],
            'file' => 'sometimes|nullable|image|max:1024',
        ];
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.categories.edit', [
            'categories' => (new CategoryRepository())
                ->makeModel()
                ->scopes('enabled')
                ->select('name', 'id')
                ->get()
                ->except($this->category->id)
        ]);
    }
}
