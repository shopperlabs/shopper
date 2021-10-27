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
    public $addresses;

    public function mount($adresses)
    {
        $this->addresses = $adresses;
    }

    public function render()
    {
        return view('shopper::livewire.customers.addresses', [
            'addresses' => Cache::remember('customer-addresses', 60 * 60 * 24, fn () => $this->addresses),
        ]);
    }
}
