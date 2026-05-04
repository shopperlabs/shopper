<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\Contracts\Address as AddressContract;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Collection<int, Address> $addresses
 * @property-read Collection<int, Address> $shippingAddresses
 * @property-read Collection<int, Address> $billingAddresses
 */
class Addresses extends Component
{
    use HandlesAuthorizationExceptions;

    /** @var Model&ShopperUser */
    #[Locked]
    public ShopperUser $customer;

    /**
     * @return Collection<int, Address>
     */
    #[Computed]
    public function addresses(): Collection
    {
        return resolve(AddressContract::class)::with('country')
            ->whereBelongsTo($this->customer)
            ->get();
    }

    /**
     * @return Collection<int, Address>
     */
    #[Computed]
    public function shippingAddresses(): Collection
    {
        return $this->addresses
            ->where('type', AddressType::Shipping)
            ->sortByDesc('shipping_default')
            ->values();
    }

    /**
     * @return Collection<int, Address>
     */
    #[Computed]
    public function billingAddresses(): Collection
    {
        return $this->addresses
            ->where('type', AddressType::Billing)
            ->sortByDesc('billing_default')
            ->values();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.customers.addresses');
    }
}
