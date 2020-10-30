<?php

namespace Shopper\Framework\Http\Livewire\Brands;

use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Models\System\File;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;

class Create extends Component
{
    use WithFileUploads;

    public $name;
    public $slug;
    public $website;
    public $description;
    public $is_enabled = false;
    public $file;

    public function store()
    {
        $this->validate($this->rules());

        $brand = (new BrandRepository())->create([
            'name' => $this->name,
            'slug' => $this->slug,
            'website' => $this->website,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
        ]);

        if ($this->file) {
            File::query()->create([
                'disk_name'     => $filename = $this->file->store('/', config('shopper.system.storage.disks.uploads')),
                'file_name'     => $this->file->getClientOriginalName(),
                'file_size'     => $this->file->getSize(),
                'content_type'  => $this->file->getClientMimeType(),
                'filetable_type' => config('shopper.system.models.brand'),
                'filetable_id'   => $brand->id,
            ]);
        }

        session()->flash('success', __("Brand successfully added!"));
        $this->redirectRoute('shopper.brands.index');
    }

    public function removeImage()
    {
        $this->file = null;
    }

    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    public function updatedName($value)
    {
        $this->slug = str_slug($value, '-');
    }

    public function rules()
    {
        return [
            'name' => 'required|max:150|unique:'.shopper_table('brands'),
            'slug' => 'required',
            'file' => 'nullable|image|max:1024',
        ];
    }

    public function render()
    {
        return view('shopper::livewire.brands.create');
    }
}
