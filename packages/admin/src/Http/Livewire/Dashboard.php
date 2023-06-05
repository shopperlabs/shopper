<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.dashboard');
    }
}
