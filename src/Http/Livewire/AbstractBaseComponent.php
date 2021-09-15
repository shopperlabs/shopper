<?php

namespace Shopper\Framework\Http\Livewire;

use Livewire\Component;

abstract class AbstractBaseComponent extends Component
{
    /**
     * Real-time component validation.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updated(string $field)
    {
        $this->validateOnly($field, $this->rules());
    }

    abstract public function rules(): array;

    abstract public function store();

    abstract public function render();
}
