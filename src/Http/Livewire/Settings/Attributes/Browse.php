<?php

namespace Shopper\Framework\Http\Livewire\Settings\Attributes;

use Livewire\Component;
use Shopper\Framework\Models\Shop\Product\Attribute;

class Browse extends Component
{
    public function render()
    {
        return view('shopper::livewire.settings.attributes.browse', [
            'total' => Attribute::query()->count(),
        ]);
    }
}
