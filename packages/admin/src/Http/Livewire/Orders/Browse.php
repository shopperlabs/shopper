<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Orders;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\Order;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.orders.browse', [
            'total' => Order::query()->count(),
        ]);
    }
}
