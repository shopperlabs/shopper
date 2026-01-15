<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\Contracts\Address as AddressContract;
use Shopper\Core\Models\Contracts\ShopperUser;

class Addresses extends Component
{
    public ShopperUser $customer;

    /**
     * @return Collection<int, Address>
     */
    #[Computed(persist: true)]
    public function addresses(): Collection
    {
        return resolve(AddressContract::class)::with('country')
            ->whereBelongsTo($this->customer) // @phpstan-ignore-line
            ->get();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.customers.addresses');
    }
}
