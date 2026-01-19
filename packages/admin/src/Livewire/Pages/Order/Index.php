<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Order;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
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
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Currency;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_orders');
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
                        'items.product',
                        'items.product.media',
                    ])
                    ->latest()
            )
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
                TextColumn::make('status')
                    ->label(__('shopper::forms.label.status'))
                    ->badge(),
                TextColumn::make('customer.first_name')
                    ->label(__('shopper::words.customer'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (Order $record): View => view(
                        'shopper::components.user-avatar',
                        ['user' => $record->customer]
                    ))
                    ->toggleable(),
                TextColumn::make('id')
                    ->label(__('shopper::words.purchased'))
                    ->formatStateUsing(fn (Order $record): View => view(
                        'shopper::livewire.tables.cells.orders.purchased',
                        ['order' => $record]
                    )),
                TextColumn::make('total')
                    ->label(__('shopper::forms.label.price_amount'))
                    ->state(fn (Order $record): string => shopper_money_format(
                        amount: $record->total(),
                        currency: $record->currency_code
                    )),
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
                SelectFilter::make('currency_code')
                    ->label(__('shopper::forms.label.currency'))
                    ->options(fn (): array => Currency::query()
                        ->whereIn('id', shopper_setting('currencies') ?? [])
                        ->pluck('code', 'code')
                        ->all()),
                Filter::make('amount')
                    ->label(__('shopper::words.amount'))
                    ->schema([
                        Slider::make('amount_range')
                            ->hiddenLabel()
                            ->range(minValue: 0, maxValue: 1000000)
                            ->step(100)
                            ->default([0, 1000000])
                            ->tooltips()
                            ->fillTrack([false, true, false]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $range = $data['amount_range'] ?? null;

                        if (! $range || ! is_array($range) || count($range) !== 2) {
                            return $query;
                        }

                        [$min, $max] = $range;

                        if ($min === 0 && $max === 1000000) {
                            return $query;
                        }

                        $table = shopper_table('order_items');

                        return $query->whereIn('id', function ($subQuery) use ($table, $min, $max): void {
                            $subQuery->select('order_id')
                                ->from($table)
                                ->groupBy('order_id')
                                ->havingRaw('SUM(quantity * unit_price_amount) >= ?', [$min])
                                ->havingRaw('SUM(quantity * unit_price_amount) <= ?', [$max]);
                        });
                    }),
            ])
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(4)
            ->emptyState(view('shopper::livewire.tables.empty-states.orders'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.orders.index')
            ->title(__('shopper::pages/orders.menu'));
    }
}
