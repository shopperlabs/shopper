<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Zones;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Zone;

#[Lazy]
class ZoneShippingOptions extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?Zone $zone = null;

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->icon('untitledui-trash-03')
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
            ->icon('untitledui-edit-03')
            ->action(fn (array $arguments) => $this->dispatch(
                'openPanel',
                component: 'shopper-slide-overs.shipping-option-form',
                arguments: ['zoneId' => $arguments['zone_id'], 'optionId' => $arguments['option_id']]
            ));
    }

    public function render(): View
    {
        return view('shopper::livewire.components.settings.zones.shipping-options');
    }
}
