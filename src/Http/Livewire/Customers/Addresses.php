<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Addresses extends Component
{
    /**
     * Customer Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $customer;

    /**
     * Component Mount instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $customer
     */
    public function mount($customer)
    {
        $this->customer = $customer;
    }

    /**
     * Return the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.customers.addresses', [
            'addresses' => Cache::remember('customer-addresses', 60*60*24, function () {
                return $this->customer->addresses;
            })
        ]);
    }
}
