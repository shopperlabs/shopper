<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Models\Contracts\Order as OrderContract;
use Shopper\Core\Models\Contracts\ShopperUser;

class Orders extends Component
{
    public ShopperUser $customer;

    #[Computed(persist: true)]
    public function orders(): Paginator
    {
        return resolve(OrderContract::class)::with([
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
