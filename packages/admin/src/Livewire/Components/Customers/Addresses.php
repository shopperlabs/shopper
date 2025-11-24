<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Contracts\ShopperUser;
use Shopper\Core\Models\Address;

class Addresses extends Component
{
    public ShopperUser $customer;

    /**
     * @return Collection<int, Address>
     */
    #[Computed(persist: true)]
    public function addresses(): Collection
    {
        return Address::with('country')
            ->whereBelongsTo($this->customer) // @phpstan-ignore-line
            ->get();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.customers.addresses');
    }
}
