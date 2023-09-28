<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Brands;

use Illuminate\Contracts\View\View;
use Shopper\Contracts\HasForm;
use Shopper\Core\Repositories\Store\BrandRepository;
use Shopper\Core\Traits\Attributes\WithSeoAttributes;
use Shopper\Http\Livewire\AbstractBaseComponent;

class Create extends AbstractBaseComponent implements HasForm
{
    use UseForm;
    use WithSeoAttributes;

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
        'shopper:fileUpdated' => 'onFileUpdate',
    ];

    public function store(): void
    {
        $brand = $this->save((new BrandRepository())->model());

        if ($this->fileUrl) {
            $brand->addMedia($this->fileUrl)->toMediaCollection(config('shopper.core.storage.collection_name'));
        }

        session()->flash('success', __('shopper::notifications.actions.create', ['item' => __('shopper::words.brand')]));

        $this->redirectRoute('shopper.brands.index');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.brands.create');
    }
}
