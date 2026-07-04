<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Order;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Concerns\HasTabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
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
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Url;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\Tables\UserColumn;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Currency;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Index extends AbstractPageComponent implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use HasTabs;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    #[Url(as: 'tab', except: 'all')]
    public ?string $activeTab = null;

    public function mount(): void
    {
        $this->authorize('orders.browse');

        $this->loadDefaultActiveTab();
    }

    /**
     * @return array<string, Tab>
     */
    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('shopper::words.all'))
                ->icon(Untitledui::LayersThree)
                ->badge(fn (): int => $this->tabCounts()['all']),
            'open' => Tab::make(__('shopper::words.open'))
                ->icon(OrderStatus::New->getIcon())
                ->badge(fn (): int => $this->tabCounts()['open'])
                ->badgeColor('success')
                ->query(
                    fn (Builder $query): Builder => $query
                        ->whereIn('status', [OrderStatus::New, OrderStatus::Processing])
                        ->where('shipping_status', ShippingStatus::Unfulfilled)
                ),
            'paid' => Tab::make(PaymentStatus::Paid->getLabel())
                ->icon(PaymentStatus::Paid->getIcon())
                ->badge(fn (): int => $this->tabCounts()['paid'])
                ->badgeColor('success')
                ->query(
                    fn (Builder $query): Builder => $query
                        ->where('payment_status', PaymentStatus::Paid)
                ),
            'fulfilled' => Tab::make(__('shopper::words.fulfilled'))
                ->icon(ShippingStatus::Shipped->getIcon())
                ->badge(fn (): int => $this->tabCounts()['fulfilled'])
                ->badgeColor('info')
                ->query(
                    fn (Builder $query): Builder => $query
                        ->whereIn('shipping_status', [ShippingStatus::Shipped, ShippingStatus::PartiallyShipped])
                ),
            'cancelled' => Tab::make(OrderStatus::Cancelled->getLabel())
                ->icon(OrderStatus::Cancelled->getIcon())
                ->badge(fn (): int => $this->tabCounts()['cancelled'])
                ->badgeColor('danger')
                ->query(
                    fn (Builder $query): Builder => $query
                        ->where('status', OrderStatus::Cancelled)
                ),
            'archived' => Tab::make(OrderStatus::Archived->getLabel())
                ->icon(OrderStatus::Archived->getIcon())
                ->badge(fn (): int => $this->tabCounts()['archived'])
                ->badgeColor('gray')
                ->query(
                    fn (Builder $query): Builder => $query
                        ->where('status', OrderStatus::Archived)
                ),
        ];
    }

    /**
     * One cached aggregate for every tab badge, instead of six live counts
     * (one of them unfiltered) on every page load.
     *
     * @return array{all: int, open: int, paid: int, fulfilled: int, cancelled: int, archived: int}
     */
    public function tabCounts(): array
    {
        return Cache::flexible('admin:orders:tab-counts', [60, 300], function (): array {
            $orders = resolve(Order::class)::query()
                ->toBase()
                ->select(['status', 'payment_status', 'shipping_status'])
                ->selectRaw('COUNT(*) as aggregate')
                ->groupBy('status', 'payment_status', 'shipping_status')
                ->get();

            $sum = fn (callable $matches): int => (int) $orders
                ->filter(fn (object $row): bool => $matches($row))
                ->sum('aggregate');

            return [
                'all' => (int) $orders->sum('aggregate'),
                'open' => $sum(fn (object $row): bool => in_array($row->status, [OrderStatus::New->value, OrderStatus::Processing->value], true)
                    && $row->shipping_status === ShippingStatus::Unfulfilled->value),
                'paid' => $sum(fn (object $row): bool => $row->payment_status === PaymentStatus::Paid->value),
                'fulfilled' => $sum(fn (object $row): bool => in_array($row->shipping_status, [ShippingStatus::Shipped->value, ShippingStatus::PartiallyShipped->value], true)),
                'cancelled' => $sum(fn (object $row): bool => $row->status === OrderStatus::Cancelled->value),
                'archived' => $sum(fn (object $row): bool => $row->status === OrderStatus::Archived->value),
            ];
        });
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
                UserColumn::make('customer.first_name')
                    ->label(__('shopper::words.customer'))
                    ->user(fn (Order $record) => $record->customer)
                    ->searchable()
                    ->sortable()
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
                            ->placeholder('ORD-XXXXX'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            $data['number'],
                            fn (Builder $query, string $number): Builder => $query->where('number', 'like', "{$number}%"),
                        )),
                SelectFilter::make('customer_id')
                    ->label(__('shopper::words.customer'))
                    ->relationship('customer', 'first_name')
                    ->searchable(),
                SelectFilter::make('status')
                    ->label(__('shopper::forms.label.status'))
                    ->options(OrderStatus::options())
                    ->multiple(),
                SelectFilter::make('payment_status')
                    ->label(__('shopper::forms.label.payment_status'))
                    ->options(PaymentStatus::options())
                    ->multiple(),
                SelectFilter::make('shipping_status')
                    ->label(__('shopper::forms.label.shipping_status'))
                    ->options(ShippingStatus::options())
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
