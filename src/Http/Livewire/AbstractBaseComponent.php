<?php

namespace Shopper\Framework\Http\Livewire;

use Livewire\Component;

abstract class AbstractBaseComponent extends Component
{
    abstract public function rules(): array;

    abstract public function store();

    abstract public function render();
}
