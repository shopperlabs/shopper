<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Dashboard;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Contracts\Product as ProductContract;

/**
 * @property-read array<int, array{label: string, value: string|int, change: float, trend: string, icon: string, route: string}> $cards
 */
final class StatCards extends Component
{
    #[Computed]
    public function cards(): array
    {
        return Cache::flexible('dashboard:stat-cards', [300, 1800], function () {
            $now = Carbon::now();
            $currency = shopper_currency();

            return [
                $this->buildRevenueCard($now, $currency),
                $this->buildProductsCard($now),
                $this->buildOrdersCard($now),
                $this->buildCustomersCard($now),
            ];
        });
    }

    public function render(): View
    {
        return view('shopper::livewire.components.dashboard.stat-cards');
    }

    /**
     * @return array{label: string, value: string, change: float, trend: string, icon: string, route: string}
     */
    private function buildRevenueCard(Carbon $now, string $currency): array
    {
        $currentMonth = (int) resolve(Order::class)::query()
            ->where('payment_status', PaymentStatus::Paid)
            ->where('currency_code', $currency)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('price_amount');

        $lastMonth = (int) resolve(Order::class)::query()
            ->where('payment_status', PaymentStatus::Paid)
            ->where('currency_code', $currency)
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->sum('price_amount');

        return [
            'label' => __('shopper::pages/dashboard.stats.revenue'),
            'value' => shopper_money_format($currentMonth / 100, $currency),
            ...$this->calculateChange($currentMonth, $lastMonth),
            'icon' => 'phosphor-coins-duotone',
            'route' => route('shopper.orders.index'),
        ];
    }

    /**
     * @return array{label: string, value: int, change: float, trend: string, icon: string, route: string}
     */
    private function buildProductsCard(Carbon $now): array
    {
        $current = resolve(ProductContract::class)::query()
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $previous = resolve(ProductContract::class)::query()
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->count();

        return [
            'label' => __('shopper::pages/dashboard.stats.products'),
            'value' => resolve(ProductContract::class)::query()->count(),
            ...$this->calculateChange($current, $previous),
            'icon' => 'phosphor-package-duotone',
            'route' => route('shopper.products.index'),
        ];
    }

    /**
     * @return array{label: string, value: int, change: float, trend: string, icon: string, route: string}
     */
    private function buildOrdersCard(Carbon $now): array
    {
        $current = resolve(Order::class)::query()
            ->where('payment_status', PaymentStatus::Paid)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $previous = resolve(Order::class)::query()
            ->where('payment_status', PaymentStatus::Paid)
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->count();

        return [
            'label' => __('shopper::pages/dashboard.stats.orders'),
            'value' => resolve(Order::class)::query()
                ->where('payment_status', PaymentStatus::Paid)
                ->count(),
            ...$this->calculateChange($current, $previous),
            'icon' => 'phosphor-shopping-bag-open-duotone',
            'route' => route('shopper.orders.index'),
        ];
    }

    /**
     * @return array{label: string, value: int, change: float, trend: string, icon: string, route: string}
     */
    private function buildCustomersCard(Carbon $now): array
    {
        $userModel = config('auth.providers.users.model');

        $current = $userModel::query()
            ->scopes('customers')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $previous = $userModel::query()
            ->scopes('customers')
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->count();

        return [
            'label' => __('shopper::pages/dashboard.stats.customers'),
            'value' => $userModel::query()->scopes('customers')->count(),
            ...$this->calculateChange($current, $previous),
            'icon' => 'phosphor-users-duotone',
            'route' => route('shopper.customers.index'),
        ];
    }

    /**
     * @return array{change: float, trend: string}
     */
    private function calculateChange(int $current, int $previous): array
    {
        if ($previous === 0) {
            return [
                'change' => $current > 0 ? 100.0 : 0.0,
                'trend' => $current > 0 ? 'up' : 'neutral',
            ];
        }

        $change = round((($current - $previous) / $previous) * 100, 1);

        return [
            'change' => abs($change),
            'trend' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'neutral'),
        ];
    }
}
