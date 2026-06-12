<?php

declare(strict_types=1);

namespace Shopper\Api\Support;

use Shopper\Cart\Models\CartAddress;
use Shopper\Core\Models\Contracts\Inventory as InventoryContract;
use Shopper\Core\Models\Inventory;
use Shopper\Shipping\DataTransferObjects\Address;

/**
 * Bridges cart and inventory records to the address DTO the shipping
 * drivers rate against. Returns null when the record cannot produce a
 * ratable address (no country), so callers fall back to manual rates.
 */
final class ShippingAddressFactory
{
    public function fromCartAddress(CartAddress $address): ?Address
    {
        $country = $address->country?->cca2;

        if (! $country) {
            return null;
        }

        return new Address(
            firstName: $address->first_name ?? '',
            lastName: $address->last_name,
            street: $address->address_1,
            city: $address->city,
            postalCode: $address->postal_code,
            state: $address->state ?? '',
            country: $country,
            company: $address->company,
            street2: $address->address_2,
            phone: $address->phone,
        );
    }

    public function fromInventory(Inventory $inventory): ?Address
    {
        $country = $inventory->country->cca2;

        if (! $country || ! $inventory->street_address) {
            return null;
        }

        return new Address(
            firstName: '',
            lastName: $inventory->name,
            street: $inventory->street_address,
            city: $inventory->city,
            postalCode: $inventory->postal_code,
            state: $inventory->state ?? '',
            country: $country,
            street2: $inventory->street_address_plus,
            phone: $inventory->phone_number,
            email: $inventory->email,
        );
    }

    /**
     * The shipment origin is the default inventory location, falling back to
     * the highest-priority one.
     */
    public function origin(): ?Address
    {
        /** @var Inventory|null $inventory */
        $inventory = resolve(InventoryContract::class)::query()
            ->with('country')
            ->orderByDesc('is_default')
            ->orderBy('priority')
            ->first();

        return $inventory ? $this->fromInventory($inventory) : null;
    }
}
