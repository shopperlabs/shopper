<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Actions\MarkShipmentDeliveredAction;
use Shopper\Core\Actions\RecordShipmentEventAction;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\OrderShipping;
use Shopper\Core\Models\OrderShippingEvent;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property-read Collection<int, OrderShippingEvent> $events
 */
class ShipmentDetail extends SlideOverComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public OrderShipping $shipment;

    public static function panelMaxWidth(): string
    {
        return '3xl';
    }

    public function mount(): void
    {
        $this->authorize('read_orders');

        $this->shipment->load('events');
    }

    /** @return Collection<int, OrderShippingEvent> */
    #[Computed]
    public function events(): Collection
    {
        return $this->shipment->events()->orderBy('occurred_at')->get();
    }

    public function addEventAction(): Action
    {
        $allowedTransitions = $this->shipment->allowedTransitions();

        return Action::make('addEvent')
            ->label(__('shopper::pages/orders.shipment.add_event'))
            ->icon(Untitledui::Plus)
            ->size(Size::Small)
            ->color('gray')
            ->modalWidth(Width::Large)
            ->visible(count($allowedTransitions) > 0)
            ->schema([
                ToggleButtons::make('status')
                    ->label(__('shopper::forms.label.status'))
                    ->options(
                        collect($allowedTransitions)
                            ->mapWithKeys(fn (ShipmentStatus $status): array => [
                                $status->value => $status->getLabel(),
                            ])
                            ->all()
                    )
                    ->icons(
                        collect($allowedTransitions)
                            ->mapWithKeys(fn (ShipmentStatus $status): array => [
                                $status->value => $status->getIcon(),
                            ])
                            ->all()
                    )
                    ->colors(
                        collect($allowedTransitions)
                            ->mapWithKeys(fn (ShipmentStatus $status): array => [
                                $status->value => $status->getColor(),
                            ])
                            ->all()
                    )
                    ->required()
                    ->inline(),
                DateTimePicker::make('occurred_at')
                    ->label(__('shopper::pages/orders.shipment.occurred_at'))
                    ->default(now())
                    ->native(false)
                    ->required(),
                TextInput::make('location')
                    ->label(__('shopper::pages/orders.shipment.location'))
                    ->prefixIcon(Untitledui::MarkerPin)
                    ->inlinePrefix()
                    ->placeholder('22 Rue Rond-Point des Champs-Élysées, 75005 Paris'),
                Textarea::make('description')
                    ->label(__('shopper::forms.label.description'))
                    ->rows(2),
                Hidden::make('latitude')
                    ->label(__('shopper::pages/orders.shipment.latitude')),
                Hidden::make('longitude')
                    ->label(__('shopper::pages/orders.shipment.longitude')),
            ])
            ->action(function (array $data): void {
                (new RecordShipmentEventAction)->execute(
                    shipment: $this->shipment,
                    status: ShipmentStatus::from($data['status']),
                    context: [
                        'description' => $data['description'] ?? null,
                        'location' => $data['location'] ?? null,
                        'latitude' => isset($data['latitude']) ? (float) $data['latitude'] : null,
                        'longitude' => isset($data['longitude']) ? (float) $data['longitude'] : null,
                        'occurred_at' => Carbon::parse($data['occurred_at']),
                    ],
                );

                unset($this->events);

                $this->shipment->refresh()->load('items.product');

                $this->dispatch('shipment-updated');

                Notification::make()
                    ->body(__('shopper::pages/orders.shipment.event_added'))
                    ->success()
                    ->send();
            });
    }

    public function markDeliveredAction(): Action
    {
        return Action::make('markDelivered')
            ->label(__('shopper::forms.actions.mark_delivered'))
            ->icon(Untitledui::PackageCheck)
            ->color('success')
            ->visible($this->shipment->canBeDelivered())
            ->requiresConfirmation()
            ->action(function (): void {
                (new MarkShipmentDeliveredAction)->execute($this->shipment);

                unset($this->events);

                $this->shipment->refresh()->load('items.product');

                $this->dispatch('shipment-updated');

                Notification::make()
                    ->title(__('shopper::pages/orders.notifications.shipment_delivered'))
                    ->success()
                    ->send();
            });
    }

    public function render(): View
    {
        $this->shipment->loadMissing('order.shippingAddress', 'carrier', 'items.product');

        return view('shopper::livewire.slide-overs.shipment-detail');
    }
}
