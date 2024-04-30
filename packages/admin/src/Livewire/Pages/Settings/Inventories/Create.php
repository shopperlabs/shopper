<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Inventories;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('shopper::components.layouts.setting')]
class Create extends Component
{
    public function mount(): void
    {
        $this->authorize('add_inventories');
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.inventories.create')
            ->title(__('shopper::pages/settings/global.location.add'));
    }
}
