<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

abstract class AbstractBaseComponent extends Component
{
    abstract public function rules(): array;

    abstract public function store(): void;

    abstract public function render(): View;
}
