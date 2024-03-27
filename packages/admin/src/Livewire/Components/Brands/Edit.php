<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Brands;

use Illuminate\Contracts\View\View;
use Shopper\Contracts\HasForm;
use Shopper\Core\Traits\Attributes\WithSeoAttributes;
use Shopper\Livewire\AbstractBaseComponent;

class Edit extends AbstractBaseComponent implements HasForm
{
    use UseForm;
    use WithSeoAttributes;

    public $brand;

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'shopper:fileUpdated' => 'onFileUpdate',
    ];

    public function mount($brand): void
    {
        $this->brand = $brand;
        $this->brandId = $brand->id;
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
        $this->save($this->brand);

        if ($this->fileUrl) {
            $this->brand->addMedia($this->fileUrl)->toMediaCollection(config('shopper.core.storage.collection_name'));
        }

        session()->flash('success', __('shopper::notifications.actions.update', ['item' => __('shopper::words.brand')]));

        $this->redirectRoute('shopper.brands.index');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.brands.edit');
    }
}
