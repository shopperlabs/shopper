<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
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
        return view('shopper::livewire.components.customers.addresses');
    }
}
