<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Inventories;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Inventory\Inventory;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.settings.inventories.browse', [
            'inventories' => Inventory::query()->with('country')->get()->take(4),
        ]);
    }
}
