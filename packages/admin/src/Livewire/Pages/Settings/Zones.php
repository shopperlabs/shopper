<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Models\Zone;

#[Layout('shopper::components.layouts.setting')]
class Zones extends Component
{
    public ?int $currentZoneId = null;

    public function updatedCurrentZoneId(int $value): void
    {
        $this->dispatch('zoneRefresh', currentZoneId: $value);
    }

    #[On('refreshZones')]
    public function render(): View
    {
        return view('shopper::livewire.pages.settings.zones', [
            'zones' => Zone::with(['carriers', 'countries'])->get(),
        ])
            ->title(__('shopper::pages/settings/zones.title'));
    }
}
