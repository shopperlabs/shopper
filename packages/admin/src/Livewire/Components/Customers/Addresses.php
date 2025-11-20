<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\User;

class Addresses extends Component
{
    public User $customer;

    /**
     * @return Collection<int, Address>
     */
    #[Computed(persist: true)]
    public function addresses(): Collection
    {
        return Address::with('country')
            ->whereBelongsTo($this->customer)
            ->get();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.customers.addresses');
    }
}
