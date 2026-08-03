<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Cart\Models\CartAddress;

/**
 * @mixin CartAddress
 */
class CartAddressResource extends JsonApiResource
{
    final public function toType(Request $request): string
    {
        return 'cart-addresses';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'type' => $this->type->value,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'company' => $this->company,
            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country_code' => $this->country?->cca2,
            'phone' => $this->phone,
        ];
    }
}
