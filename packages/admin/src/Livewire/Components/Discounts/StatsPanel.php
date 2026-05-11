<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Discounts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Discount;

class StatsPanel extends Component
{
    #[Locked]
    public Discount $discount;

    public function mount(Discount $discount): void
    {
        $this->discount = $discount;
    }

    /**
     * @return array{
     *     usage: int,
     *     usage_limit: ?int,
     *     usage_remaining: ?int,
     *     gross_revenue: int,
     *     discount_cost: int,
     *     orders_count: int,
     *     aov_with: int
     * }
     */
    #[Computed(persist: true, seconds: 60)]
    public function stats(): array
    {
        $paidStatuses = [OrderStatus::Processing, OrderStatus::Completed];

        $ordersQuery = fn (): HasMany => $this->discount->orders()
            ->whereIn('status', $paidStatuses)
            ->where('payment_status', PaymentStatus::Paid);

        $ordersCount = $ordersQuery()->count();
        $grossRevenue = (int) $ordersQuery()->sum('price_amount');

        $discountCost = (int) DB::table(shopper_table('order_items'))
            ->join(
                shopper_table('orders'),
                shopper_table('orders').'.id',
                '=',
                shopper_table('order_items').'.order_id',
            )
            ->where(shopper_table('orders').'.discount_id', $this->discount->id)
            ->whereIn(shopper_table('orders').'.status', array_map(fn (OrderStatus $s) => $s->value, $paidStatuses))
            ->where(shopper_table('orders').'.payment_status', PaymentStatus::Paid->value)
            ->sum(shopper_table('order_items').'.discount_amount');

        return [
            'usage' => $this->discount->total_use,
            'usage_limit' => $this->discount->usage_limit,
            'usage_remaining' => $this->discount->usage_limit !== null
                ? max(0, $this->discount->usage_limit - $this->discount->total_use)
                : null,
            'gross_revenue' => $grossRevenue,
            'discount_cost' => $discountCost,
            'orders_count' => $ordersCount,
            'aov_with' => $ordersCount > 0 ? (int) round($grossRevenue / $ordersCount) : 0,
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.components.discounts.stats-panel', [
            'stats' => $this->stats,
        ]);
    }
}
