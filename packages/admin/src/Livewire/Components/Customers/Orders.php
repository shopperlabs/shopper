<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\User;

class Orders extends Component
{
    public User $customer;

    #[Computed(persist: true)]
    public function orders(): Paginator
    {
        return Order::with([
            'items',
            'items.product',
            'items.product.media',
            'shippingAddress',
            'paymentMethod',
            'shippingOption',
        ])
            ->whereBelongsTo($this->customer, 'customer')
            ->latest()
            ->simplePaginate(3);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.customers.orders');
    }
}
