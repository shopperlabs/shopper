<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Shopper\Core\Models\Zone;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Collection<int, Zone> $zones
 */
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

    /**
     * @return Collection<int, Zone>
     */
    #[Computed]
    public function zones(): Collection
    {
        return Zone::with(['carriers', 'countries'])->get();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.zones')
            ->title(__('shopper::pages/settings/zones.title'));
    }
}
