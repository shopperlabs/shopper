<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Carbon\Carbon;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Contracts\SlideOverForm;
use Shopper\Core\Actions\RecordShipmentEventAction;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\OrderShipping;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\InteractsWithSlideOverForm;

/**
 * @property-read Schema $form
 */
class ShipmentAddEvent extends SlideOverComponent implements HasActions, HasSchemas, SlideOverForm
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithSlideOverForm;

    public OrderShipping $shipment;

    /** @var array<array-key, mixed>|null */
    public ?array $data = [];

    public string $action = 'save';

    public ?string $title = null;

    public ?string $description = null;

    public static function panelMaxWidth(): string
    {
        return '3xl';
    }

    public function mount(): void
    {
        $this->authorize('read_orders');

        $this->form->fill();

        $this->title = __('shopper::pages/orders.shipment.add_event');
    }

    public function form(Schema $schema): Schema
    {
        $allowedTransitions = $this->shipment->allowedTransitions();

        return $schema
            ->components([
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
                    ->minDate($this->shipment->events()->latest('occurred_at')->value('occurred_at'))
                    ->seconds(false)
                    ->native(false)
                    ->closeOnDateSelection()
                    ->required(),
                TextInput::make('location')
                    ->label(__('shopper::pages/orders.shipment.location'))
                    ->prefixIcon(Untitledui::MarkerPin)
                    ->inlinePrefix()
                    ->placeholder('22 Rue Rond-Point Déido, 00237 Douala'),
                Textarea::make('description')
                    ->label(__('shopper::forms.label.description'))
                    ->rows(2),
                Hidden::make('latitude')
                    ->label(__('shopper::pages/orders.shipment.latitude')),
                Hidden::make('longitude')
                    ->label(__('shopper::pages/orders.shipment.longitude')),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

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

        $this->dispatch('shipment-updated');
        $this->closePanel();

        Notification::make()
            ->title(__('shopper::pages/orders.shipment.event_added'))
            ->success()
            ->send();
    }
}
