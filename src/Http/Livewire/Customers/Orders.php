<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public Model $customer;

    /**
     * Component Mount instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $customer
     */
    public function mount($customer)
    {
        $this->customer = $customer;
    }

    public function render()
    {
        return view('shopper::livewire.customers.orders', [
            'orders' => $this->customer->orders()
                ->with(['customer', 'items', 'shippingAddress', 'paymentMethod'])
                ->simplePaginate(3),
        ]);
    }
}
