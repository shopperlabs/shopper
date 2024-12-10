<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Country;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->country(),
            'name_official' => $this->faker->country(),
            'region' => $this->faker->word(),
            'cca2' => $this->faker->countryCode(),
            'cca3' => $this->faker->countryISOAlpha3(),
            'flag' => $this->faker->randomAscii(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'phone_calling_code' => [
                'root' => '+3',
                'suffixes' => ['55'],
            ],
            'currencies' => [
                $this->faker->currencyCode() => [
                    'name' => $this->faker->name(),
                    'symbol' => $this->faker->name(),
                ],
            ],
        ];
    }
}
