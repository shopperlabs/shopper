<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\Concerns\WithSettingsBreadcrumbs;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Collection<int, Zone> $zones
 */
#[Layout('shopper::components.layouts.setting')]
class Zones extends Component
{
    use HandlesAuthorizationExceptions;
    use WithSettingsBreadcrumbs;

    #[Url(as: 'zone', except: '')]
    public ?int $currentZoneId = null;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    public function settingsPageBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: __('shopper::pages/settings/zones.title')),
        ];
    }

    public function mount(): void
    {
        $this->authorize('system.settings');

        if ($this->currentZoneId === null) {
            $this->currentZoneId = $this->zones->first()?->id;
        }
    }

    public function updatedCurrentZoneId(int $value): void
    {
        $this->dispatch('zone.changed', currentZoneId: $value);
    }

    #[On('zone-deleted')]
    public function onZoneDeleted(): void
    {
        unset($this->zones);

        $this->currentZoneId = $this->zones->first()?->id;
    }

    /**
     * @return Collection<int, Zone>
     */
    #[Computed]
    public function zones(): Collection
    {
        return Zone::query()
            ->with(['countries', 'currency'])
            ->when(
                $this->search,
                fn ($query) => $query->where(
                    fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                        ->orWhereHas('countries', fn ($c) => $c->where('name', 'like', "%{$this->search}%"))
                )
            )
            ->get();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.zones')
            ->title(__('shopper::pages/settings/zones.title'));
    }
}
