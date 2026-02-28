<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Contracts\ShopperUser;

class Orders extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    /** @var Model&ShopperUser */
    public ShopperUser $customer;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                resolve(Order::class)::query()
                    ->with([
                        'items',
                        'items.product',
                        'items.product.media',
                        'zone',
                        'channel',
                    ])
                    ->whereBelongsTo($this->customer, 'customer')
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
                    ->sortable(),
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
            ->filters([
                SelectFilter::make('status')
                    ->label(__('shopper::forms.label.status'))
                    ->options(\Shopper\Core\Enum\OrderStatus::class)
                    ->multiple(),
                SelectFilter::make('payment_status')
                    ->label(__('shopper::forms.label.payment_status'))
                    ->options(\Shopper\Core\Enum\PaymentStatus::class)
                    ->multiple(),
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
            ->emptyState(view('shopper::livewire.tables.empty-states.orders'));
    }

    public function render(): View
    {
        return view('shopper::livewire.components.customers.orders');
    }
}
