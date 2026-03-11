<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Search extends Component
{
    use HandlesAuthorizationExceptions;

    public string $search = '';

    public function render(): View
    {
        return view('shopper::livewire.components.search');
    }
}
