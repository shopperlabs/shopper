<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\OrderAddress;

/**
 * @mixin OrderAddress
 */
class OrderAddressResource extends JsonApiResource
{
    final public function toType(Request $request): string
    {
        return 'order-addresses';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'company' => $this->company,
            'street_address' => $this->street_address,
            'street_address_plus' => $this->street_address_plus,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
            'country_name' => $this->country_name,
        ];
    }
}
