<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Settings\Attributes;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\Attribute;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.settings.attributes.browse', [
            'total' => Attribute::query()->count(),
        ]);
    }
}
