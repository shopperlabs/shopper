<?php

namespace Shopper\Framework\Http\Livewire\Brands;

use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Traits\{WithSeoAttributes, WithUploadProcess};

class Edit extends AbstractBaseComponent
{
    use WithFileUploads, WithUploadProcess, WithSeoAttributes;

    public $brand;
    public int $brand_id;
    public string $name;
    public ?string $website = null;
    public ?string $description = null;
    public bool $is_enabled = false;

    public $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    protected $listeners = [
        'mediaDeleted',
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function onTrixValueUpdate($value)
    {
        $this->description = $value;
    }

    /**
     * Component mount instance.
     */
    public function mount($brand)
    {
        $this->brand = $brand;
        $this->brand_id = $brand->id;
        $this->name = $brand->name;
        $this->website = $brand->website;
        $this->description = $brand->description;
        $this->is_enabled = $brand->is_enabled;
        $this->updateSeo = true;
        $this->seoTitle = $brand->seo_title;
        $this->seoDescription = $brand->seo_description;
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

    public function store(): void
    {
        $this->validate($this->rules());

        $this->brand->update([
            'name' => $this->name,
            'slug' => $this->name,
            'website' => $this->website,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
            'seo_title' => $this->seoTitle,
            'seo_description' => str_limit($this->seoDescription, 157),
        ]);

        if ($this->file) {
            $this->brand->addMedia($this->file->getRealPath())->toMediaCollection(config('shopper.system.storage.disks.uploads'));
        }

        session()->flash('success', __('Brand successfully updated!'));

        $this->redirectRoute('shopper.brands.index');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:150',
                Rule::unique(shopper_table('brands'), 'name')->ignore($this->brand_id),
            ],
            'file' => 'nullable|image|max:1024',
        ];
    }

    /**
     * Listen when a file is removed from the storage
     * and update the user screen and remove image preview.
     */
    public function mediaDeleted()
    {
        $this->media = null;
    }

    public function render()
    {
        return view('shopper::livewire.brands.edit', [
            'media' => $this->brand->getFirstMedia(config('shopper.system.storage.disks.uploads')),
        ]);
    }
}
