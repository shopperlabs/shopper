<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Customers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Addresses extends Component
{
    public Collection $addresses;

    public function mount($adresses): void
    {
        $this->addresses = $adresses;
    }

    public function render(): View
    {
        return view('shopper::livewire.customers.addresses', [
            'addresses' => Cache::remember('customer-addresses', 60 * 60 * 24, fn () => $this->addresses),
        ]);
    }
}
