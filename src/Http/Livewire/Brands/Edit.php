<?php

namespace Shopper\Framework\Http\Livewire\Brands;

use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Traits\WithSeoAttributes;

class Edit extends AbstractBaseComponent
{
    use WithSeoAttributes;

    public $brand;

    public int $brand_id;

    public string $name;

    public ?string $website = null;

    public ?string $description = null;

    public bool $is_enabled = false;

    public ?string $fileUrl = null;

    public $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    protected $listeners = [
        'shopper:fileUpdated' => 'onFileUpdate',
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function onTrixValueUpdate($value)
    {
        $this->description = $value;
    }

    public function onFileUpdate($file)
    {
        $this->fileUrl = $file;
    }

    public function mount($brand)
    {
        $this->brand = $brand;
        $this->brand_id = $brand->id;
        $this->name = $brand->name;
        $this->website = $brand->website;
        $this->description = $brand->description;
        $this->is_enabled = $brand->is_enabled;
        $this->updateSeo = true;
        $this->seoTitle = $brand->seo_title ?? $brand->name;
        $this->seoDescription = $brand->seo_description;
    }

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

        if ($this->fileUrl) {
            $this->brand->addMedia($this->fileUrl)->toMediaCollection(config('shopper.system.storage.disks.uploads'));
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
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.brands.edit');
    }
}
