<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Order;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Concerns\HasTabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Actions\MarkShipmentDeliveredAction;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\OrderShipping;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Shipping\Services\CarrierRateService;

class Shipments extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use HasTabs;
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_orders');

        $this->loadDefaultActiveTab();
    }

    #[On('shipment-updated')]
    public function refreshTable(): void
    {
        $this->resetTable();
    }

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('shopper::words.all'))
                ->icon(Untitledui::LayersThree)
                ->badge(fn (): int => OrderShipping::query()->count()),
            'pending' => Tab::make(ShipmentStatus::Pending->getLabel())
                ->icon(ShipmentStatus::Pending->getIcon())
                ->badge(fn (): int => OrderShipping::query()->where('status', ShipmentStatus::Pending)->count())
                ->badgeColor('warning')
                ->query(fn (Builder $query): Builder => $query->where('status', ShipmentStatus::Pending)),
            'in_transit' => Tab::make(ShipmentStatus::InTransit->getLabel())
                ->icon(ShipmentStatus::InTransit->getIcon())
                ->badge(fn (): int => OrderShipping::query()->whereIn('status', [
                    ShipmentStatus::PickedUp,
                    ShipmentStatus::InTransit,
                    ShipmentStatus::AtSortingCenter,
                    ShipmentStatus::OutForDelivery,
                ])->count())
                ->badgeColor('primary')
                ->query(fn (Builder $query): Builder => $query->whereIn('status', [
                    ShipmentStatus::PickedUp,
                    ShipmentStatus::InTransit,
                    ShipmentStatus::AtSortingCenter,
                    ShipmentStatus::OutForDelivery,
                ])),
            'delivered' => Tab::make(ShipmentStatus::Delivered->getLabel())
                ->icon(ShipmentStatus::Delivered->getIcon())
                ->badge(fn (): int => OrderShipping::query()->where('status', ShipmentStatus::Delivered)->count())
                ->badgeColor('success')
                ->query(fn (Builder $query): Builder => $query->where('status', ShipmentStatus::Delivered)),
            'failed' => Tab::make(ShipmentStatus::DeliveryFailed->getLabel())
                ->icon(ShipmentStatus::DeliveryFailed->getIcon())
                ->badge(fn (): int => OrderShipping::query()->where('status', ShipmentStatus::DeliveryFailed)->count())
                ->badgeColor('danger')
                ->query(fn (Builder $query): Builder => $query->where('status', ShipmentStatus::DeliveryFailed)),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderShipping::query()
                    ->with(['order', 'carrier', 'items', 'events'])
                    ->latest()
            )
            ->modifyQueryUsing($this->modifyQueryWithActiveTab(...))
            ->columns([
                TextColumn::make('id')
                    ->label(__('shopper::pages/orders.shipment_id'))
                    ->formatStateUsing(fn (OrderShipping $record): string => 'SHP-'.$record->id)
                    ->searchable()
                    ->sortable(),
                ViewColumn::make('shipment_event')
                    ->label(__('shopper::pages/orders.shipment_event'))
                    ->view('shopper::livewire.tables.cells.orders.shipment-stepper'),
                TextColumn::make('status')
                    ->label(__('shopper::forms.label.status'))
                    ->badge()
                    ->color(fn (OrderShipping $record): ?string => $record->status?->getColor())
                    ->icon(fn (OrderShipping $record): ?string => $record->status?->getIcon())
                    ->formatStateUsing(fn (OrderShipping $record): string => $record->status?->getLabel() ?? '—'),
                TextColumn::make('shipped_at')
                    ->label(__('shopper::forms.label.shipped_at'))
                    ->date()
                    ->sortable(),
                TextColumn::make('order.number')
                    ->label(__('shopper::pages/orders.order_number'))
                    ->searchable()
                    ->sortable()
                    ->url(fn (OrderShipping $record): string => route('shopper.orders.detail', $record->order)),
                ViewColumn::make('carrier')
                    ->label(__('shopper::pages/orders.carrier_service'))
                    ->view('shopper::livewire.tables.cells.orders.shipment-carrier'),
            ])
            ->recordActions([
                Action::make('markDelivered')
                    ->label(__('shopper::forms.actions.mark_delivered'))
                    ->icon(Untitledui::PackageCheck)
                    ->color('success')
                    ->visible(fn (OrderShipping $record): bool => $record->canBeDelivered())
                    ->requiresConfirmation()
                    ->action(function (OrderShipping $record): void {
                        (new MarkShipmentDeliveredAction)->execute($record);

                        Notification::make()
                            ->title(__('shopper::pages/orders.notifications.shipment_delivered'))
                            ->success()
                            ->send();
                    }),
                Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->modalWidth(Width::Large)
                    ->fillForm(fn (OrderShipping $record): array => [
                        'carrier_id' => $record->carrier_id,
                        'tracking_number' => $record->tracking_number,
                        'tracking_url' => $record->tracking_url,
                    ])
                    ->schema([
                        Select::make('carrier_id')
                            ->label(__('shopper::pages/orders.carrier_service'))
                            ->options(fn (): array => resolve(CarrierRateService::class)->getCarrierSelectOptions())
                            ->native(false)
                            ->searchable()
                            ->allowHtml()
                            ->required(),
                        TextInput::make('tracking_number')
                            ->label(__('shopper::forms.label.tracking_number'))
                            ->placeholder('e.g. 1Z999AA10123456784'),
                        TextInput::make('tracking_url')
                            ->label(__('shopper::forms.label.tracking_url'))
                            ->placeholder('https://your-tracking-url.com')
                            ->url(),
                    ])
                    ->action(function (OrderShipping $record, array $data): void {
                        $record->update($data);

                        Notification::make()
                            ->title(__('shopper::notifications.update', ['item' => __('shopper::pages/orders.shipments')]))
                            ->success()
                            ->send();
                    }),
                Action::make('view')
                    ->label(__('shopper::words.details'))
                    ->icon(Untitledui::Eye)
                    ->iconButton()
                    ->action(fn (OrderShipping $record) => $this->dispatch(
                        'openPanel',
                        component: 'shopper-slide-overs.shipment-detail',
                        arguments: ['shipment' => $record],
                    )),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('shopper::forms.label.status'))
                    ->options(ShipmentStatus::class)
                    ->multiple(),
                SelectFilter::make('order_id')
                    ->label(__('shopper::pages/orders.order_number'))
                    ->relationship('order', 'number', fn (Builder $query): Builder => $query->whereHas('shippings'))
                    ->searchable()
                    ->preload()
                    ->optionsLimit(10),
                SelectFilter::make('carrier_id')
                    ->label(__('shopper::pages/orders.carrier_service'))
                    ->relationship('carrier', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.orders.shipments')
            ->title(__('shopper::pages/orders.shipments'));
    }
}
