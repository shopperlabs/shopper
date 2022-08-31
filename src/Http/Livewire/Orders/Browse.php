<?php

namespace Shopper\Framework\Http\Livewire\Orders;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Order\Order;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.orders.browse', [
            'total' => Order::query()->count(),
        ]);
    }
}
