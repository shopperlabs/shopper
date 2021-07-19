<?php

namespace Shopper\Framework\Http\Livewire\Categories;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\System\File;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Traits\WithUploadProcess;

class Edit extends AbstractBaseComponent
{
    use WithFileUploads, WithUploadProcess;

    public $category;

    public int $categoryId;

    public string $name = '';

    public ?int $parent_id = null;

    public ?string $description = null;

    public bool $is_enabled = false;

    protected $listeners = ['fileDeleted'];

    public function mount($category)
    {
        $this->category = $category;
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->parent_id = $category->parent_id;
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

        $this->category->update([
            'name' => $this->name,
            'slug' => $this->name,
            'parent_id' => $this->parent_id,
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
            'file' => 'sometimes|nullable|image|max:1024',
        ];
    }

    /**
     * Listen when a file is removed from the storage
     * and update the user screen and remove image preview.
     *
     * @return void
     */
    public function fileDeleted()
    {
        $this->media = null;
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
            'media' => $this->category->files->isNotEmpty()
                ? $this->category->files->first()
                : null,
        ]);
    }
}
