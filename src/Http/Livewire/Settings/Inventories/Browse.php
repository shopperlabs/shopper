<?php

namespace Shopper\Framework\Http\Livewire\Settings\Inventories;

use Livewire\Component;
use Shopper\Framework\Models\Shop\Inventory\Inventory;

class Browse extends Component
{
    public function render()
    {
        return view('shopper::livewire.settings.inventories.browse', [
            'inventories' => Inventory::query()->with('country')->get()->take(4),
        ]);
    }
}
