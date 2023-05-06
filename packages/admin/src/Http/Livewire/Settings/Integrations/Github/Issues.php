<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Integrations\Github;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Issues extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.settings.integrations.github.issues');
    }
}
