<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Order;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Cart\Models\Cart;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

class AbandonedCarts extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use HandlesAuthorizationExceptions;
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
                Cart::query()
                    ->whereNull('completed_at')
                    ->whereHas('lines')
                    ->where('updated_at', '<=', now()->subMinutes(
                        (int) config('shopper.cart.abandoned_after_minutes', 60)
                    ))
                    ->with(['customer', 'lines', 'channel'])
                    ->latest('updated_at')
            )
            ->recordActions([
                Action::make('view')
                    ->label(__('shopper::words.details'))
                    ->icon(Untitledui::Eye)
                    ->iconButton()
                    ->action(fn (Cart $record) => $this->dispatch(
                        'openPanel',
                        component: 'shopper-slide-overs.abandoned-cart-detail',
                        arguments: ['cart' => $record],
                    )),
            ])
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('customer.full_name')
                    ->label(__('shopper::words.customer'))
                    ->default(__('shopper::pages/orders.abandoned_carts.guest')),
                TextColumn::make('lines_count')
                    ->label(__('shopper::pages/orders.abandoned_carts.items'))
                    ->counts('lines')
                    ->sortable(),
                TextColumn::make('currency_code')
                    ->label(__('shopper::forms.label.currency'))
                    ->badge(),
                TextColumn::make('channel.name')
                    ->label(__('shopper::pages/orders.abandoned_carts.channel'))
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label(__('shopper::forms.label.created_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('shopper::pages/orders.abandoned_carts.last_activity'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('customer_id')
                    ->label(__('shopper::words.customer'))
                    ->options(fn (): array => Cart::query()
                        ->whereNull('completed_at')
                        ->whereHas('lines')
                        ->where('updated_at', '<=', now()->subMinutes(
                            (int) config('shopper.cart.abandoned_after_minutes', 60)
                        ))
                        ->whereNotNull('customer_id')
                        ->with('customer')
                        ->get()
                        ->pluck('customer.name', 'customer_id')
                        ->filter()
                        ->all())
                    ->searchable(),
                Filter::make('created_at')
                    ->label(__('shopper::forms.label.created_at'))
                    ->schema([
                        DatePicker::make('from')
                            ->label(__('shopper::words.from')),
                        DatePicker::make('to')
                            ->label(__('shopper::words.to')),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'], fn (Builder $q, string $date): Builder => $q->where('created_at', '>=', $date))
                        ->when($data['to'], fn (Builder $q, string $date): Builder => $q->where('created_at', '<=', $date))),
                SelectFilter::make('channel_id')
                    ->label(__('shopper::pages/orders.abandoned_carts.channel'))
                    ->relationship('channel', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->emptyStateHeading(__('shopper::pages/orders.abandoned_carts.empty'))
            ->emptyStateDescription(__('shopper::pages/orders.abandoned_carts.empty_description'))
            ->emptyStateIcon(Untitledui::ShoppingBag02);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.orders.abandoned-carts')
            ->title(__('shopper::pages/orders.abandoned_carts.title'));
    }
}
