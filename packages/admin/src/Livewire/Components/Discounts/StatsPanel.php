<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Discounts;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Contracts\Order as OrderContract;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\OrderPromotion;

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

        $promotions = fn (): Builder => OrderPromotion::query()
            ->where('discount_id', $this->discount->id)
            ->whereHas('order', fn (Builder $query): Builder => $query
                ->whereIn('status', $paidStatuses)
                ->where('payment_status', PaymentStatus::Paid));

        $orderIds = $promotions()->pluck('order_id');
        $ordersCount = $orderIds->count();
        $grossRevenue = (int) resolve(OrderContract::class)::query()
            ->whereKey($orderIds)
            ->sum('price_amount');

        $discountCost = (int) $promotions()->sum('amount');

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
        return view('shopper::livewire.components.discounts.stats-panel');
    }
}
