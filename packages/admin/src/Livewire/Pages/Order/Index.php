<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Order;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Shopper\Core\Models\Order;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasForms, HasTable
{
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
                Order::query()
                    ->with([
                        'customer',
                        'items',
                        'zone',
                    ])
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('#')
                    ->searchable()
                    ->extraAttributes(['class' => 'uppercase'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('shopper::words.date'))
                    ->date()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('shopper::forms.label.status'))
                    ->badge(),

                Tables\Columns\TextColumn::make('customer.first_name')
                    ->label(__('shopper::words.customer'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (Order $model): View => view(
                        'shopper::livewire.tables.cells.orders.customer',
                        ['order' => $model->load('customer')]
                    ))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('id')
                    ->label(__('shopper::words.purchased'))
                    ->formatStateUsing(fn (Order $model): View => view(
                        'shopper::livewire.tables.cells.orders.purchased',
                        ['order' => $model->load('items')]
                    )),

                Tables\Columns\TextColumn::make('currency_code')
                    ->label(__('shopper::forms.label.price_amount'))
                    ->formatStateUsing(
                        fn ($state, Order $record): string => shopper_money_format(amount: $record->total(), currency: $state)
                    ),

                Tables\Columns\TextColumn::make('zone.name')
                    ->label(__('shopper::pages/settings/zones.single'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('shopper::words.details'))
                    ->url(
                        fn (Order $record): string => route(
                            name: 'shopper.orders.detail',
                            parameters: ['order' => $record]
                        ),
                    ),
            ])
            ->filters([]);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.orders.index', [
            'total' => Order::query()->count(),
        ])
            ->title(__('shopper::pages/orders.menu'));
    }
}
