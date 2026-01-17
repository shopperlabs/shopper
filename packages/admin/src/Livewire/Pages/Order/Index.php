<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Order;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Shopper\Core\Models\Contracts\Order;
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
                        'shopper::livewire.tables.cells.orders.customer',
                        ['order' => $record]
                    ))
                    ->toggleable(),
                TextColumn::make('id')
                    ->label(__('shopper::words.purchased'))
                    ->formatStateUsing(fn (Order $record): View => view(
                        'shopper::livewire.tables.cells.orders.purchased',
                        ['order' => $record]
                    )),
                TextColumn::make('currency_code')
                    ->label(__('shopper::forms.label.price_amount'))
                    ->formatStateUsing(
                        fn (string $state, Order $record): string => shopper_money_format(amount: $record->total(), currency: $state)
                    ),
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
                    ->url(
                        fn (Order $record): string => route(
                            name: 'shopper.orders.detail',
                            parameters: ['order' => $record]
                        ),
                    ),
            ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.orders.index')
            ->title(__('shopper::pages/orders.menu'));
    }
}
