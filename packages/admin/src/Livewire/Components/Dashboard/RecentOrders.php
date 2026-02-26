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
        return Cache::flexible(
            'dashboard:recent-orders',
            [60, 300],
            fn (): Collection => resolve(Order::class)::query()
                ->whereNotIn('status', [OrderStatus::Cancelled, OrderStatus::Archived])
                ->with(['customer', 'items', 'items.product', 'items.product.media'])
                ->latest()
                ->take(7)
                ->get()
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.components.dashboard.recent-orders');
    }
}
