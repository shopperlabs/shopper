<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Orders extends Component
{
    public $customer;

    public function mount($customer): void
    {
        $this->customer = $customer->load(['orders']);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.customers.orders', [
            'orders' => $this->customer->orders()
                ->with(['items', 'shippingAddress', 'paymentMethod', 'shippingOption'])
                ->simplePaginate(3),
        ]);
    }
}
