<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Customers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\Tables\UserColumn;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read array{total: int, new_count: int, active_count: int, active_percent: int, avg_ltv: int} $stats
 */
class Index extends AbstractPageComponent implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('customers.browse');
    }

    /**
     * `#[Computed]` only memoizes within one Livewire lifecycle: the shared
     * cache below is what keeps the full-table aggregate off every page view.
     *
     * @return array{total: int, new_count: int, active_count: int, active_percent: int, avg_ltv: int}
     */
    #[Computed]
    public function stats(): array
    {
        return Cache::flexible('admin:customers:stats:'.shopper_currency(), [300, 1800], fn (): array => $this->computeStats());
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
                UserColumn::make('first_name')
                    ->label(__('shopper::forms.label.full_name'))
                    ->url(fn ($record): string => route('shopper.customers.show', $record))
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
                        fn (ShopperUser $record): ?string => $record->addresses->first()?->country?->translated_name // @phpstan-ignore-line
                    ),
                TextColumn::make('orders_count')
                    ->counts([
                        'orders' => fn (Builder $query) => $query
                            ->whereIn('status', [
                                OrderStatus::Processing,
                                OrderStatus::Completed,
                            ])
                            ->where('payment_status', PaymentStatus::Paid),
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

    /**
     * @return array{total: int, new_count: int, active_count: int, active_percent: int, avg_ltv: int}
     */
    private function computeStats(): array
    {
        $userModel = config('auth.providers.users.model');
        $usersTable = (new $userModel)->getTable();

        $activeOrders = resolve(Order::class)::query()
            ->select('customer_id')
            ->whereIn('status', [
                OrderStatus::Processing,
                OrderStatus::Completed,
            ])
            ->where('payment_status', PaymentStatus::Paid)
            ->groupBy('customer_id');

        $currencyOrders = resolve(Order::class)::query()
            ->select('customer_id')
            ->selectRaw('SUM(price_amount) as per_customer_total')
            ->whereIn('status', [
                OrderStatus::Processing,
                OrderStatus::Completed,
            ])
            ->where('payment_status', PaymentStatus::Paid)
            ->where('currency_code', shopper_currency())
            ->groupBy('customer_id');

        /** @var object{total: int, new_count: int, active_count: int, avg_ltv: float|string|null}|null $row */
        $row = $userModel::query()
            ->customers()
            ->leftJoinSub($activeOrders, 'active_orders', 'active_orders.customer_id', '=', "{$usersTable}.id")
            ->leftJoinSub($currencyOrders, 'currency_orders', 'currency_orders.customer_id', '=', "{$usersTable}.id")
            ->selectRaw(
                "COUNT(DISTINCT {$usersTable}.id) as total,
                 COUNT(DISTINCT CASE WHEN {$usersTable}.created_at >= ? THEN {$usersTable}.id END) as new_count,
                 COUNT(DISTINCT CASE WHEN active_orders.customer_id IS NOT NULL THEN {$usersTable}.id END) as active_count,
                 COALESCE(AVG(currency_orders.per_customer_total), 0) as avg_ltv",
                [now()->subDays(30)->toDateTimeString()]
            )
            ->first();

        $total = (int) ($row->total ?? 0);
        $active = (int) ($row->active_count ?? 0);

        return [
            'total' => $total,
            'new_count' => (int) ($row->new_count ?? 0),
            'active_count' => $active,
            'active_percent' => $total > 0 ? (int) round(($active / $total) * 100) : 0,
            'avg_ltv' => (int) round((float) ($row->avg_ltv ?? 0)),
        ];
    }
}
