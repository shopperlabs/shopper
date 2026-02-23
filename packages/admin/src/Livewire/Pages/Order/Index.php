<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Order;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Concerns\HasTabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Currency;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasActions, HasForms, HasTable
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

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        $orderModel = resolve(Order::class);

        return [
            'all' => Tab::make(__('shopper::words.all'))
                ->icon(Untitledui::LayersThree)
                ->badge(fn (): int => $orderModel::query()->count()),
            'open' => Tab::make(__('shopper::words.open'))
                ->icon(OrderStatus::New->getIcon())
                ->badge(
                    fn (): int => $orderModel::query()
                        ->whereIn('status', [OrderStatus::New, OrderStatus::Processing])
                        ->where('shipping_status', ShippingStatus::Unfulfilled)
                        ->count()
                )
                ->badgeColor('success')
                ->query(
                    fn (Builder $query): Builder => $query
                        ->whereIn('status', [OrderStatus::New, OrderStatus::Processing])
                        ->where('shipping_status', ShippingStatus::Unfulfilled)
                ),
            'paid' => Tab::make(PaymentStatus::Paid->getLabel())
                ->icon(PaymentStatus::Paid->getIcon())
                ->badge(
                    fn (): int => $orderModel::query()
                        ->where('payment_status', PaymentStatus::Paid)
                        ->count()
                )
                ->badgeColor('success')
                ->query(
                    fn (Builder $query): Builder => $query
                        ->where('payment_status', PaymentStatus::Paid)
                ),
            'fulfilled' => Tab::make(__('shopper::words.fulfilled'))
                ->icon(ShippingStatus::Shipped->getIcon())
                ->badge(
                    fn (): int => $orderModel::query()
                        ->whereIn('shipping_status', [ShippingStatus::Shipped, ShippingStatus::PartiallyShipped])
                        ->count()
                )
                ->badgeColor('info')
                ->query(
                    fn (Builder $query): Builder => $query
                        ->whereIn('shipping_status', [ShippingStatus::Shipped, ShippingStatus::PartiallyShipped])
                ),
            'cancelled' => Tab::make(OrderStatus::Cancelled->getLabel())
                ->icon(OrderStatus::Cancelled->getIcon())
                ->badge(
                    fn (): int => $orderModel::query()
                        ->where('status', OrderStatus::Cancelled)
                        ->count()
                )
                ->badgeColor('danger')
                ->query(
                    fn (Builder $query): Builder => $query
                        ->where('status', OrderStatus::Cancelled)
                ),
            'archived' => Tab::make(OrderStatus::Archived->getLabel())
                ->icon(OrderStatus::Archived->getIcon())
                ->badge(
                    fn (): int => $orderModel::query()
                        ->where('status', OrderStatus::Archived)
                        ->count()
                )
                ->badgeColor('gray')
                ->query(
                    fn (Builder $query): Builder => $query
                        ->where('status', OrderStatus::Archived)
                ),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                resolve(Order::class)::query()
                    ->with([
                        'customer',
                        'items',
                        'zone',
                        'channel',
                        'items.product',
                        'items.product.media',
                    ])
                    ->latest()
            )
            ->modifyQueryUsing($this->modifyQueryWithActiveTab(...))
            ->columns([
                TextColumn::make('number')
                    ->label('#')
                    ->searchable()
                    ->extraAttributes(['class' => 'uppercase'])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('shopper::words.date'))
                    ->date()
                    ->sortable()
                    ->toggleable(),
                ViewColumn::make('status')
                    ->label(__('shopper::forms.label.status'))
                    ->view('shopper::livewire.tables.cells.orders.status')
                    ->extraCellAttributes(['class' => 'whitespace-nowrap']),
                TextColumn::make('total')
                    ->label(__('shopper::forms.label.price_amount'))
                    ->state(fn (Order $record): string => shopper_money_format(
                        amount: $record->total(),
                        currency: $record->currency_code
                    )),
                TextColumn::make('id')
                    ->label(__('shopper::words.purchased'))
                    ->formatStateUsing(fn (Order $record): View => view(
                        'shopper::livewire.tables.cells.orders.purchased',
                        ['order' => $record]
                    )),
                TextColumn::make('customer.first_name')
                    ->label(__('shopper::words.customer'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (Order $record): View => view(
                        'shopper::components.user-avatar',
                        ['user' => $record->customer]
                    ))
                    ->toggleable(),
                TextColumn::make('currency_code')
                    ->label(__('shopper::forms.label.currency'))
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                TextColumn::make('zone.name')
                    ->label(__('shopper::pages/settings/zones.single'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                TextColumn::make('channel.name')
                    ->label(__('shopper::pages/settings/channels.single'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label(__('shopper::words.details'))
                    ->icon(Untitledui::Eye)
                    ->iconButton()
                    ->action(fn (Order $record) => $this->redirectRoute(
                        name: 'shopper.orders.detail',
                        parameters: ['order' => $record],
                        navigate: true
                    )),
            ])
            ->filters([
                Filter::make('number')
                    ->label('#')
                    ->schema([
                        TextInput::make('number')
                            ->label(__('shopper::words.number'))
                            ->placeholder('SHP-XXXXX'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            $data['number'],
                            fn (Builder $query, string $number): Builder => $query->where('number', 'like', "%{$number}%"),
                        )),
                SelectFilter::make('customer_id')
                    ->label(__('shopper::words.customer'))
                    ->relationship('customer', 'first_name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label(__('shopper::forms.label.status'))
                    ->options(OrderStatus::class)
                    ->multiple(),
                SelectFilter::make('payment_status')
                    ->label(__('shopper::forms.label.payment_status'))
                    ->options(PaymentStatus::class)
                    ->multiple(),
                SelectFilter::make('shipping_status')
                    ->label(__('shopper::forms.label.shipping_status'))
                    ->options(ShippingStatus::class)
                    ->multiple(),
                Filter::make('created_at')
                    ->label(__('shopper::words.date'))
                    ->schema([
                        DatePicker::make('created_from')
                            ->label(__('shopper::words.from'))
                            ->native(false),
                        DatePicker::make('created_until')
                            ->label(__('shopper::words.to'))
                            ->native(false),
                    ])
                    ->columns()
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, mixed $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, mixed $date): Builder => $query->whereDate('created_at', '<=', $date),
                        )),
                SelectFilter::make('zone_id')
                    ->label(__('shopper::pages/settings/zones.single'))
                    ->relationship('zone', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('channel_id')
                    ->label(__('shopper::pages/settings/channels.single'))
                    ->relationship('channel', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('currency_code')
                    ->label(__('shopper::forms.label.currency'))
                    ->options(fn (): array => Currency::query()
                        ->whereIn('id', shopper_setting('currencies') ?? [])
                        ->pluck('code', 'code')
                        ->all()),
            ])
            ->filtersLayout(FiltersLayout::Modal)
            ->filtersFormColumns(2)
            ->emptyState(view('shopper::livewire.tables.empty-states.orders'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.orders.index')
            ->title(__('shopper::pages/orders.menu'));
    }
}
