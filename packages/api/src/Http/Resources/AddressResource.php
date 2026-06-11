<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\Address;

/**
 * @mixin Address
 */
final class AddressResource extends JsonApiResource
{
    public function toType(Request $request): string
    {
        return 'addresses';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company_name' => $this->company_name,
            'street_address' => $this->street_address,
            'street_address_plus' => $this->street_address_plus,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
            'state' => $this->state,
            'country_code' => $this->country->cca2,
            'type' => $this->type->value,
            'phone_number' => $this->phone_number,
            'shipping_default' => $this->shipping_default,
            'billing_default' => $this->billing_default,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    public function toRelationships(Request $request): array
    {
        return [
            'country' => fn () => CountryResource::make($this->country),
        ];
    }
}
