<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Addresses extends Component
{
    public Collection $addresses;

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
