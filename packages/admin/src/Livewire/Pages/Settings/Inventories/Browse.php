<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Inventories;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Core\Models\Inventory;

#[Layout('shopper::components.layouts.setting')]
class Browse extends Component
{
    public function mount(): void
    {
        $this->authorize('browse_inventories');
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.inventories.browse', [
            'inventories' => Inventory::query()
                ->with('country')
                ->get()
                ->take(config('shopper.admin.inventory-limit')),
        ])->title(__('shopper::words.locations'));
    }
}
