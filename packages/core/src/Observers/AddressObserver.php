<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Models\Address;

final class AddressObserver
{
    public function creating(Address $address): void
    {
        $this->ensureOnlyOneDefaultShipping($address);
        $this->ensureOnlyOneDefaultBilling($address);
    }

    public function updating(Address $address): void
    {
        $this->ensureOnlyOneDefaultShipping($address);
        $this->ensureOnlyOneDefaultBilling($address);
    }

    protected function ensureOnlyOneDefaultShipping(Address $address): void
    {
        if ($address->shipping_default) {
            $defaultAddress = Address::query()
                ->where('id', '!=', $address->id)
                ->where('user_id', $address->user_id)
                ->where('shipping_default', true)
                ->first();

            if ($defaultAddress) {
                $defaultAddress->shipping_default = false;
                $defaultAddress->saveQuietly();
            }
        }
    }

    protected function ensureOnlyOneDefaultBilling(Address $address): void
    {
        if ($address->billing_default) {
            $defaultAddress = Address::query()
                ->where('id', '!=', $address->id)
                ->where('user_id', $address->user_id)
                ->where('billing_default', true)
                ->first();

            if ($defaultAddress) {
                $defaultAddress->billing_default = false;
                $defaultAddress->saveQuietly();
            }
        }
    }
}
