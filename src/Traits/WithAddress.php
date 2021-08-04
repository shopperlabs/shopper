<?php

namespace Shopper\Framework\Traits;

use Shopper\Framework\Rules\Phone;

trait WithAddress
{
    /**
     * Address First name.
     *
     * @var string
     */
    public $address_first_name;

    /**
     * Address Last name.
     *
     * @var string
     */
    public $address_last_name;

    /**
     * Companyt name.
     *
     * @var string
     */
    public $company_name;

    /**
     * Country Id.
     *
     * @var int
     */
    public $country_id;

    /**
     * Address Zip code.
     *
     * @var string
     */
    public $zipcode;

    /**
     * Address city.
     *
     * @var string
     */
    public $city;

    /**
     * The default street name.
     *
     * @var string
     */
    public $street_address;

    /**
     * Street name more detail.
     *
     * @var string
     */
    public $street_address_plus;

    /**
     * Address Phone number.
     *
     * @var string
     */
    public $address_phone_number;

    /**
     * Address validation.
     *
     * @return array<string>
     */
    public function addressRules()
    {
        return [
            'address_first_name' => 'required',
            'address_last_name' => 'required',
            'street_address' => 'required',
            'country_id' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'address_phone_number' => [
                'nullable',
                new Phone(),
            ],
        ];
    }
}
