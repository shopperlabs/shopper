<?php

namespace Shopper\Framework\Http\Livewire\Brands;

use Livewire\WithFileUploads;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Traits\WithUploadProcess;
use Shopper\Framework\Http\Livewire\AbstractBaseComponent;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;

class Create extends AbstractBaseComponent
{
    use WithFileUploads;
    use WithUploadProcess;
    use WithSeoAttributes;

    public string $name = '';
    public ?string $website = null;
    public ?string $description = null;
    public bool $is_enabled = true;

    public $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function onTrixValueUpdate($value)
    {
        $this->description = $value;
    }

    public function store(): void
    {
        $this->validate($this->rules());

        $brand = (new BrandRepository())->create([
            'name' => $this->name,
            'slug' => $this->name,
            'website' => $this->website,
            'description' => $this->description,
            'is_enabled' => $this->is_enabled,
            'seo_title' => $this->seoTitle,
            'seo_description' => $this->seoDescription,
        ]);

        if ($this->file) {
            $this->uploadFile('brand', $brand->id);
        }

        session()->flash('success', __('Brand successfully added!'));

        $this->redirectRoute('shopper.brands.index');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:150|unique:' . shopper_table('brands'),
            'file' => 'nullable|image|max:1024',
        ];
    }

    public function render()
    {
        return view('shopper::livewire.brands.create');
    }
}
