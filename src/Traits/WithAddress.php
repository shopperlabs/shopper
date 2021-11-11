<?php

namespace Shopper\Framework\Traits;

use Shopper\Framework\Rules\Phone;

trait WithAddress
{
    public ?string $address_first_name = null;
    public ?string $address_last_name = null;
    public ?string $company_name = null;
    public ?int $country_id = null;
    public string $zipcode = '';
    public string $city = '';
    public string $street_address = '';
    public ?string $street_address_plus = null;
    public ?string $address_phone_number = null;

    public function addressRules(): array
    {
        return [
            'address_first_name' => 'required',
            'address_last_name' => 'required',
            'street_address' => 'required',
            'country_id' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'address_phone_number' => ['nullable', new Phone()],
        ];
    }
}
