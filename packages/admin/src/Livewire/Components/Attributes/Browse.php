<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Attributes;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\Attribute;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.components.attributes.browse', [
            'total' => Attribute::query()->count(),
        ]);
    }
}
