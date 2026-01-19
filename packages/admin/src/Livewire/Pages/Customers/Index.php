<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Customers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Models\Contracts\ShopperUser;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_customers');
    }

    public function table(Table $table): Table
    {
        $userModel = config('auth.providers.users.model');

        return $table
            ->query(
                $userModel::query()
                    ->with(['addresses', 'addresses.country'])
                    ->scopes('customers')
                    ->latest()
            )
            ->columns([
                ViewColumn::make('first_name')
                    ->label(__('shopper::forms.label.full_name'))
                    ->view('shopper::livewire.tables.cells.customers.name')
                    ->searchable()
                    ->sortable(),
                ViewColumn::make('email')
                    ->label(__('shopper::forms.label.email'))
                    ->view('shopper::livewire.tables.cells.customers.email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('country')
                    ->label(__('shopper::forms.label.country'))
                    ->getStateUsing(
                        fn (ShopperUser $record): ?string => $record->addresses->first()?->country?->name // @phpstan-ignore-line
                    )
                    ->sortable(),
                TextColumn::make('orders_count')
                    ->counts([
                        'orders' => fn (Builder $query) => $query->whereIn('status', [
                            OrderStatus::Paid,
                            OrderStatus::Shipped,
                            OrderStatus::Delivered,
                            OrderStatus::Completed,
                        ]),
                    ])
                    ->label(__('shopper::pages/customers.orders.placed')),
                TextColumn::make('created_at')
                    ->label(__('shopper::forms.label.registered_at'))
                    ->date()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label(__('shopper::forms.actions.view'))
                    ->icon(Untitledui::Eye)
                    ->iconButton()
                    ->action(fn (ShopperUser $record) => $this->redirectRoute(
                        name: 'shopper.customers.show',
                        parameters: ['user' => $record],
                        navigate: true
                    )),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label(__('shopper::forms.label.email_verified'))
                    ->nullable(),
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('created_from')
                            ->native(false),
                        DatePicker::make('created_until')
                            ->native(false),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, mixed $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, mixed $date): Builder => $query->whereDate('created_at', '<=', $date),
                        )),
            ])
            ->persistFiltersInSession()
            ->emptyState(view('shopper::livewire.tables.empty-states.customers'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.customers.index')
            ->title(__('shopper::pages/customers.menu'));
    }
}
