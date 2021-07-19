<?php

namespace Shopper\Framework\Http\Livewire;

use Livewire\Component;

abstract class AbstractBaseComponent extends Component
{
    /**
     * Real-time component validation.
     *
     * @param  string  $field
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return void
     */
    public function updated(string $field)
    {
        $this->validateOnly($field, $this->rules());
    }

    /**
     * Store/Update a entry to the storage.
     *
     * @return void
     */
    abstract public function store();

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    abstract public function render();

    /**
     * Component validation rules.
     *
     * @return array<string>
     */
    abstract protected function rules();
}
