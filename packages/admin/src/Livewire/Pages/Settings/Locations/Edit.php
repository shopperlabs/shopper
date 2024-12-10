<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Locations;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Core\Models\Inventory;

#[Layout('shopper::components.layouts.setting')]
class Edit extends Component
{
    public Inventory $inventory;

    public function mount(): void
    {
        $this->authorize('edit_inventories');
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.locations.edit')
            ->title(__('shopper::pages/settings/global.location.menu') . ' ~ ' . $this->inventory->name);
    }
}
