<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Dashboard;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Enum\OrderRefundStatus;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\OrderRefund;

/**
 * @property-read array<int, array{label: string, value: string|int, change: float, trend: string, icon: string, route: string}> $cards
 */
final class StatCards extends Component
{
    #[Computed]
    public function cards(): array
    {
        $currency = shopper_currency();

        return Cache::flexible('dashboard:stat-cards:'.app()->getLocale().':'.$currency, [300, 1800], function () use ($currency) {
            $now = Carbon::now();

            return [
                $this->buildRevenueCard($now, $currency),
                $this->buildProductsCard($now),
                $this->buildOrdersCard($now),
                $this->buildCustomersCard($now),
            ];
        });
    }

    public function placeholder(): View
    {
        return view('shopper::livewire.components.dashboard.placeholders.stat-cards');
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
        $currentMonth = $this->netRevenue($currency, $this->monthToDateRange($now));
        $lastMonth = $this->netRevenue($currency, $this->previousMonthToDateRange($now));

        return [
            'label' => __('shopper::pages/dashboard.stats.revenue'),
            'value' => shopper_money_format($currentMonth, $currency),
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
            ->whereBetween('created_at', $this->monthToDateRange($now))
            ->count();

        $previous = resolve(ProductContract::class)::query()
            ->whereBetween('created_at', $this->previousMonthToDateRange($now))
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
            ->whereNotIn('status', [OrderStatus::Cancelled, OrderStatus::Archived])
            ->whereBetween('created_at', $this->monthToDateRange($now))
            ->count();

        $previous = resolve(Order::class)::query()
            ->whereNotIn('status', [OrderStatus::Cancelled, OrderStatus::Archived])
            ->whereBetween('created_at', $this->previousMonthToDateRange($now))
            ->count();

        return [
            'label' => __('shopper::pages/dashboard.stats.orders'),
            'value' => resolve(Order::class)::query()
                ->whereNotIn('status', [OrderStatus::Cancelled, OrderStatus::Archived])
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
            ->whereBetween('created_at', $this->monthToDateRange($now))
            ->count();

        $previous = $userModel::query()
            ->scopes('customers')
            ->whereBetween('created_at', $this->previousMonthToDateRange($now))
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
     * @param  array{0: Carbon, 1: Carbon}  $period
     */
    private function netRevenue(string $currency, array $period): int
    {
        $collected = (int) resolve(Order::class)::query()
            ->whereIn('payment_status', PaymentStatus::revenueBearing())
            ->where('currency_code', $currency)
            ->whereBetween('created_at', $period)
            ->sum('price_amount');

        $refunded = (int) OrderRefund::query()
            ->whereIn('status', OrderRefundStatus::settled())
            ->where('currency', $currency)
            ->whereBetween('created_at', $period)
            ->sum('amount');

        return $collected - $refunded;
    }

    /**
     * @return array{change: float, trend: string}
     */
    private function calculateChange(int $current, int $previous): array
    {
        if ($previous <= 0) {
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

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function monthToDateRange(Carbon $now): array
    {
        return [$now->copy()->startOfMonth(), $now->copy()];
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function previousMonthToDateRange(Carbon $now): array
    {
        $end = $now->copy()->subMonthNoOverflow();

        return [$end->copy()->startOfMonth(), $end];
    }
}
