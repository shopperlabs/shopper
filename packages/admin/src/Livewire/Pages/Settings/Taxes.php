<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Shopper\Core\Models\TaxZone;

#[Layout('shopper::components.layouts.setting')]
class Taxes extends Component
{
    #[Url(as: 'zone', except: '')]
    public ?int $currentTaxZoneId = null;

    public function mount(): void
    {
        $this->authorize('access_setting');
    }

    public function updatedCurrentTaxZoneId(int $value): void
    {
        $this->currentTaxZoneId = $value;

        $this->dispatch('tax-zone.changed', currentTaxZoneId: $value);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.taxes', [
            'taxZones' => TaxZone::with('country')->get(),
        ])
            ->title(__('shopper::pages/settings/taxes.title'));
    }
}
