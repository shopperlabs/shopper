<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Contracts\ShopperUser;

class Orders extends Component
{
    /** @var Model&ShopperUser */
    public ShopperUser $customer;

    #[Computed]
    public function orders(): Paginator
    {
        return resolve(Order::class)::with([
            'items',
            'items.product',
            'items.product.media',
            'shippingAddress',
            'paymentMethod',
            'shippingOption',
        ])
            ->whereBelongsTo($this->customer, 'customer')
            ->latest()
            ->simplePaginate(5);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.customers.orders');
    }
}
