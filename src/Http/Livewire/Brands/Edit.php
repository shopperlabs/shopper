<?php

namespace Shopper\Framework\Http\Livewire\Brands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Models\System\File;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Traits\WithUploadProcess;

class Edit extends AbstractBaseComponent
{
    use WithFileUploads, WithUploadProcess;

    /**
     * Upload listeners.
     *
     * @var string[]
     */
    protected $listeners = ['fileDeleted'];

    /**
     * Brand Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $brand;

    /**
     * Brand Model id.
     *
     * @var int
     */
    public $brand_id;

    /**
     * Name attribute.
     *
     * @var string
     */
    public $name = '';

    /**
     * Slug for custom url.
     *
     * @var string
     */
    public $slug;

    /**
     * Brand url website.
     *
     * @var string
     */
    public $website = '';

    /**
     * Brand sample description.
     *
     * @var string
     */
    public $description = '';

    /**
     * Indicates if brand is being enabled.
     *
     * @var bool
     */
    public $is_enabled = false;

    /**
     * Component mounted action.
     *
     * @param  $brand
     * @return void
     */
    public function mount($brand)
    {
        $this->brand = $brand;
        $this->brand_id = $brand->id;
        $this->name = $brand->name;
        $this->slug = $brand->slug;
        $this->website = $brand->website;
        $this->description = $brand->description;
        $this->is_enabled = $brand->is_enabled;
    }

    /**
     * Update brand record in the database.
     *
     * @return void
     */
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

            $this->uploadFile(config('shopper.system.models.brand'), $this->brand->id);
        }

        session()->flash('success', __("Brand successfully updated!"));
        $this->redirectRoute('shopper.brands.index');
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

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.brands.edit', [
            'media' => $this->brand->files->isNotEmpty()
                ? $this->brand->files->first()
                : null,
        ]);
    }
}
