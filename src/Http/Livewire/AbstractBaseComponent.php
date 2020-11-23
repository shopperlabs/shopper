<?php

namespace Shopper\Framework\Http\Livewire;

use Livewire\Component;

abstract class AbstractBaseComponent extends Component
{
    /**
     * Real-time component validation.
     *
     * @param  string  $field
     * @throws \Illuminate\Validation\ValidationException
     * @return void
     */
    public function updated(string $field)
    {
        $this->validateOnly($field, $this->rules());
    }

    /**
     * Component validation rules.
     *
     * @return string[]
     */
    abstract protected function rules();

    /**
     * Store/Update a entry to the storage.
     *
     * @return void
     */
    abstract public function store();
}
