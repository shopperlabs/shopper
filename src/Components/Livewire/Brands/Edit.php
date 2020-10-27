<?php

namespace Shopper\Framework\Components\Livewire\Brands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Models\Shop\Product\Brand;
use Shopper\Framework\Models\System\File;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;

class Edit extends Component
{
    use WithFileUploads;

    public $brand;
    public $brand_id;
    public $name = '';
    public $slug = '';
    public $website = '';
    public $description = '';
    public $is_enabled = false;
    public $file;

    public function mount(Brand $brand)
    {
        $this->brand = $brand;
        $this->brand_id = $brand->id;
        $this->name = $brand->name;
        $this->slug = $brand->slug;
        $this->website = $brand->website;
        $this->description = $brand->description;
        $this->is_enabled = $brand->is_enabled;
    }

    public function store()
    {
        $this->validate($this->rules());

        (new BrandRepository())->getById($this->brand->id)->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'website' => $this->website,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
        ]);

        if ($this->file) {

            if ($this->brand->files->isNotEmpty()) {
                foreach ($this->brand->files as $file) {
                    Storage::disk(config('shopper.system.storage.disks.uploads'))->delete($file->disk_name);
                }
                File::query()->where('filetable_id', $this->brand_id)->delete();
            }

            File::query()->create([
                'disk_name'     => $filename = $this->file->store('/', config('shopper.system.storage.disks.uploads')),
                'file_name'     => $this->file->getClientOriginalName(),
                'file_size'     => $this->file->getSize(),
                'content_type'  => $this->file->getClientMimeType(),
                'filetable_type' => config('shopper.system.models.brand'),
                'filetable_id'   => $this->brand->id,
            ]);
        }

        session()->flash('success', __("Brand successfully updated!"));
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
            'name' => [
                'required',
                'max:150',
                Rule::unique(shopper_table('brands'), 'name')->ignore($this->brand_id),
            ],
            'slug' => [
                'required',
                Rule::unique(shopper_table('brands'), 'slug')->ignore($this->brand_id),
            ],
            'file' => 'nullable|image|max:1024',
        ];
    }

    public function render()
    {
        return view('shopper::livewire.brands.edit');
    }
}
