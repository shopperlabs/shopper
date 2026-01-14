<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Models\Contracts\Address;

class AddressObserver
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

    private function ensureOnlyOneDefaultShipping(Address $address): void
    {
        if ($address->shipping_default) {
            $defaultAddress = resolve(Address::class)::query()
                ->where('id', '!=', $address->id)
                ->where('user_id', $address->user_id)
                ->where('shipping_default', true)
                ->first();

            if ($defaultAddress instanceof Address) {
                $defaultAddress->updateQuietly([
                    'shipping_default' => false,
                ]);
            }
        }
    }

    private function ensureOnlyOneDefaultBilling(Address $address): void
    {
        if ($address->billing_default) {
            $defaultAddress = resolve(Address::class)::query()
                ->where('id', '!=', $address->id)
                ->where('user_id', $address->user_id)
                ->where('billing_default', true)
                ->first();

            if ($defaultAddress instanceof Address) {
                $defaultAddress->updateQuietly([
                    'billing_default' => false,
                ]);
            }
        }
    }
}
