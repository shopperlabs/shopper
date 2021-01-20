<?php

namespace Shopper\Framework\Http\Livewire\Customers;

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
            'addresses' => $this->customer->addresses->load('country')
        ]);
    }
}
