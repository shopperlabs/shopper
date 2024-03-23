<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Inventories;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\Inventory;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.components.settings.inventories.browse', [
            'inventories' => Inventory::query()
                ->with('country')
                ->get()
                ->take(4),
        ]);
    }
}
