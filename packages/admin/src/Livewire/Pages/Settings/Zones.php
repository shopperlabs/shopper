<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Shopper\Core\Models\Zone;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Layout('shopper::components.layouts.setting')]
class Zones extends Component
{
    use HandlesAuthorizationExceptions;

    #[Url(as: 'zone', except: '')]
    public ?int $currentZoneId = null;

    public function mount(): void
    {
        $this->authorize('access_setting');
    }

    public function updatedCurrentZoneId(int $value): void
    {
        $this->currentZoneId = $value;

        $this->dispatch('zone.changed', currentZoneId: $value);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.zones', [
            'zones' => Zone::with(['carriers', 'countries'])->get(),
        ])
            ->title(__('shopper::pages/settings/zones.title'));
    }
}
