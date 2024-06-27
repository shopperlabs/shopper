<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Shopper\Core\Models\Zone;

#[Layout('shopper::components.layouts.setting')]
class Zones extends Component
{
    #[Url(as: 'zone-id', except: '')]
    public ?int $currentZoneId = null;

    public function updatedCurrentZoneId(int $value): void
    {
        $this->currentZoneId = $value;

        $this->dispatch('refresh-zone', currentZoneId: $value);
    }

    #[On('refresh-zones')]
    public function render(): View
    {
        return view('shopper::livewire.pages.settings.zones', [
            'zones' => Zone::with(['carriers', 'countries'])->get(),
        ])
            ->title(__('shopper::pages/settings/zones.title'));
    }
}
