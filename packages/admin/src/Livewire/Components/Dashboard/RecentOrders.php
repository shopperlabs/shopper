<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Dashboard;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Models\Contracts\Order;

/**
 * @property-read Collection $orders
 */
final class RecentOrders extends Component
{
    #[Computed]
    public function orders(): Collection
    {
        /** @var array<int, int> $ids */
        $ids = Cache::flexible(
            'dashboard.orders:recent-ids',
            [60, 300],
            fn (): array => resolve(Order::class)::query()
                ->whereNotIn('status', [OrderStatus::Cancelled, OrderStatus::Archived])
                ->latest()
                ->take(7)
                ->pluck('id')
                ->all()
        );

        if ($ids === []) {
            return new Collection;
        }

        return resolve(Order::class)::query()
            ->whereIn('id', $ids)
            ->with(['customer', 'items', 'items.product', 'items.product.media'])
            ->latest()
            ->get();
    }

    public function placeholder(): View
    {
        return view('shopper::livewire.components.dashboard.placeholders.recent-orders');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.dashboard.recent-orders');
    }
}
