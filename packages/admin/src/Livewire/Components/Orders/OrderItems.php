<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Orders;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Core\Models\Contracts\Order;

class OrderItems extends Component
{
    use WithPagination;

    public Order $order;

    public int $perPage = 3;

    #[On('order.updated')]
    #[On('order.shipping.created')]
    public function render(): View
    {
        return view('shopper::livewire.components.orders.order-items', [
            'items' => $this->order
                ->items()
                ->with('product', 'product.media', 'product.prices')
                ->simplePaginate($this->perPage),
        ]);
    }
}
