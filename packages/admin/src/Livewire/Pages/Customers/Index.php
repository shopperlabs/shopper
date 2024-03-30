<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Customers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Models\Country;
use Shopper\Core\Repositories\UserRepository;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_customers');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                (new UserRepository())
                    ->with('roles')
                    ->makeModel()
                    ->scopes('customers')
                    ->newQuery()
            )
            ->columns([
                Tables\Columns\ViewColumn::make('first_name')
                    ->label(__('shopper::layout.forms.label.full_name'))
                    ->view('shopper::livewire.tables.cells.customers.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ViewColumn::make('email')
                    ->label(__('shopper::layout.forms.label.email'))
                    ->view('shopper::livewire.tables.cells.customers.email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country')
                    ->label(__('shopper::layout.forms.label.country'))
                    ->getStateUsing(
                        fn ($record): ?string => Country::find($record->addresses->first()?->country_id)?->name ?? null
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('orders_count')
                    ->counts([
                        'orders' => fn (Builder $query) => $query->where('status', OrderStatus::PAID->value),
                    ])
                    ->label(__('shopper::pages/customers.orders.placed')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('shopper::layout.forms.label.registered_at'))
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label(__('shopper::layout.forms.label.email_verified'))
                    ->nullable(),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->native(false),
                        DatePicker::make('created_until')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('shopper::layout.forms.actions.view'))
                    ->color('info')
                    ->url(fn ($record): string => route('shopper.customers.show', $record)),
            ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.customers.index', [
            'total' => (new UserRepository())
                ->makeModel()
                ->scopes('customers')
                ->count(),
        ])
            ->title(__('shopper::layout.sidebar.customers'));
    }
}
