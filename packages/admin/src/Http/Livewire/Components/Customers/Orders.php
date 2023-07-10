<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Customers;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public $customer;

    public function mount($customer): void
    {
        $this->customer = $customer;
    }

    public function paginationSimpleView(): string
    {
        return 'shopper::livewire.wire-mobile-pagination-links';
    }

    public function render(): View
    {
        return view('shopper::livewire.customers.orders', [
            'orders' => $this->customer->orders()
                ->with(['customer', 'items', 'shippingAddress', 'paymentMethod'])
                ->simplePaginate(3),
        ]);
    }
}
