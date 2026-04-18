<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Shopper\Core\Models\TaxZone;
use Shopper\Livewire\Concerns\WithSettingsBreadcrumbs;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Collection<int, TaxZone> $taxZones
 */
#[Layout('shopper::components.layouts.setting')]
class Taxes extends Component
{
    use HandlesAuthorizationExceptions;
    use WithSettingsBreadcrumbs;

    #[Url(as: 'tax-zone', except: '')]
    public ?int $currentTaxZoneId = null;

    public function settingsPageBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: __('shopper::pages/settings/taxes.title')),
        ];
    }

    public function mount(): void
    {
        $this->authorize('system.settings');
    }

    public function updatedCurrentTaxZoneId(int $value): void
    {
        $this->currentTaxZoneId = $value;

        $this->dispatch('tax-zone.changed', currentTaxZoneId: $value);
    }

    /**
     * @return Collection<int, TaxZone>
     */
    #[Computed]
    public function taxZones(): Collection
    {
        return TaxZone::with('country')->get();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.taxes')
            ->title(__('shopper::pages/settings/taxes.title'));
    }
}
