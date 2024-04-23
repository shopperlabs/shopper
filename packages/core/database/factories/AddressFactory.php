<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\Country;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'company_name' => $this->faker->company(),
            'street_address' => $this->faker->streetAddress(),
            'street_address_plus' => $this->faker->streetSuffix(),
            'city' => $this->faker->city(),
            'postcode' => $this->faker->postcode(),
            'type' => $this->faker->randomElement(['billing', 'shipping']),
            'shipping_default' => $this->faker->boolean,
            'billing_default' => $this->faker->boolean,
        ];
    }
}
