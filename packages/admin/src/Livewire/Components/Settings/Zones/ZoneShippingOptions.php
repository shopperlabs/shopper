<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Zones;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Zone;

/**
 * @property-read Zone $zone
 */
#[Lazy]
class ZoneShippingOptions extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?int $selectedZoneId = null;

    #[On('zone.changed')]
    public function updatedSelectedZone(int $currentZoneId): void
    {
        $this->selectedZoneId = $currentZoneId;
    }

    #[Computed]
    public function zone(): ?Zone
    {
        return Zone::with(['shippingOptions', 'shippingOptions.carrier'])->find($this->selectedZoneId);
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->icon(Untitledui::Trash03)
            ->color('danger')
            ->iconButton()
            ->action(function (array $arguments): void {
                CarrierOption::query()->find($arguments['id'])->delete();

                Notification::make()
                    ->title(__('shopper::notifications.delete', ['item' => __('shopper::pages/settings/zones.shipping_options.single')]))
                    ->success()
                    ->send();

                $this->dispatch('$refresh');
            });
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->iconButton()
            ->icon(Untitledui::Edit03)
            ->action(fn (array $arguments) => $this->dispatch(
                'openPanel',
                component: 'shopper-slide-overs.shipping-option-form',
                arguments: ['zoneId' => $arguments['zone_id'], 'optionId' => $arguments['option_id']]
            ));
    }

    public function placeholder(): View
    {
        return view('shopper::placeholders.detail-zone');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.settings.zones.shipping-options');
    }
}
