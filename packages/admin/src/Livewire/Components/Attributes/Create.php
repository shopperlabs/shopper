<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Attributes;

use Illuminate\Contracts\View\View;
use Shopper\Contracts\HasForm;
use Shopper\Core\Models\Attribute;
use Shopper\Livewire\AbstractBaseComponent;

class Create extends AbstractBaseComponent implements HasForm
{
    use UseForm;

    public string $model = Attribute::class;

    protected $listeners = ['selectedIcon'];

    public function store(): void
    {
        $attribute = $this->save($this->model);

        session()->flash('success', __('shopper::pages/attributes.notifications.created'));

        $this->redirectRoute('shopper.attributes.edit', $attribute);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.attributes.create', [
            'fields' => Attribute::typesFields(),
        ]);
    }
}
