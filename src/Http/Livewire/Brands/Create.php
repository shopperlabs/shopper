<?php

namespace Shopper\Framework\Http\Livewire\Brands;

use Livewire\WithFileUploads;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Traits\WithUploadProcess;

class Create extends AbstractBaseComponent
{
    use WithFileUploads, WithUploadProcess;

    /**
     * Brand name attribute.
     *
     * @var string
     */
    public $name;

    /**
     * Brand slug attribute for manage SEO.
     *
     * @var string
     */
    public $slug;

    /**
     * Brand website url.
     *
     * @var string
     */
    public $website;

    /**
     * Brand full description.
     *
     * @var string
     */
    public $description;

    /**
     * Determine if brand is enabled.
     *
     * @var bool
     */
    public $is_enabled = true;

    /**
     * Store/Update a entry to the storage.
     *
     * @return void
     */
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
            $this->uploadFile(config('shopper.system.models.brand'), $brand->id);
        }

        session()->flash('success', __("Brand successfully added!"));
        $this->redirectRoute('shopper.brands.index');
    }

    /**
     * Update dynamically slug when updated brand name.
     *
     * @param  string  $value
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
            'name' => 'required|max:150|unique:'.shopper_table('brands'),
            'slug' => 'required',
            'file' => 'nullable|image|max:1024',
        ];
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.brands.create');
    }
}
