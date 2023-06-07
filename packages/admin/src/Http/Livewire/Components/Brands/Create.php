<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Brands;

use Illuminate\Contracts\View\View;
use Shopper\Http\Livewire\AbstractBaseComponent;
use Shopper\Core\Repositories\Ecommerce\BrandRepository;
use Shopper\Core\Traits\Attributes\WithSeoAttributes;

class Create extends AbstractBaseComponent
{
    use WithSeoAttributes;

    public string $name = '';

    public ?string $website = null;

    public ?string $description = null;

    public bool $is_enabled = true;

    public ?string $fileUrl = null;

    public $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'shopper:fileUpdated' => 'onFileUpdate',
    ];

    public function onTrixValueUpdate(string $value): void
    {
        $this->description = $value;
    }

    public function onFileUpdate($file): void
    {
        $this->fileUrl = $file;
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

        if ($this->fileUrl) {
            $brand->addMedia($this->fileUrl)->toMediaCollection(config('shopper.core.storage.collection_name'));
        }

        session()->flash('success', __('Brand successfully added!'));

        $this->redirectRoute('shopper.brands.index');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:150|unique:' . shopper_table('brands'),
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.brands.create');
    }
}
