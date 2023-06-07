<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Settings\Inventories;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\Inventory;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.settings.inventories.browse', [
            'inventories' => Inventory::query()
                ->with('country')
                ->get()
                ->take(4),
        ]);
    }
}
