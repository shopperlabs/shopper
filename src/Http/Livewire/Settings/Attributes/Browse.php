<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Attributes;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Product\Attribute;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.settings.attributes.browse', [
            'total' => Attribute::query()->count(),
        ]);
    }
}
