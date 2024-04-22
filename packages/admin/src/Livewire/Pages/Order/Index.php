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
            ->query(Order::query()->with('customer'))
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge(),

                Tables\Columns\TextColumn::make('customer.first_name')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (Order $record): string => $record->customer->full_name)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('shopper::words.date'))
                    ->date()
                    ->toggleable(),
            ])
            ->actions([])
            ->filters([]);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.orders.index', [
            'total' => Order::query()->count(),
        ])
            ->title(__('shopper::layout.sidebar.orders'));
    }
}
