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

    public string $name = '';

    public ?int $parent_id = null;

    public ?string $description = null;

    public bool $is_enabled = true;

    public function store(): void
    {
        $this->validate($this->rules());

        $category = (new CategoryRepository())->create([
            'name' => $this->name,
            'slug' => $this->name,
            'parent_id' => $this->parent_id,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
        ]);

        if ($this->file) {
            $this->uploadFile(config('shopper.system.models.category'), $category->id);
        }

        session()->flash('success', __('Category successfully added!'));

        $this->redirectRoute('shopper.categories.index');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:150|unique:'.shopper_table('categories'),
            'file' => 'nullable|image|max:1024',
        ];
    }

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
