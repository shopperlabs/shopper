<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Currency;

/**
 * @extends Factory<Currency>
 */
class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currencies = [
            ['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€', 'format' => '1.234,56 €'],
            ['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'format' => '$1,234.56'],
            ['name' => 'British Pound', 'code' => 'GBP', 'symbol' => '£', 'format' => '£1,234.56'],
        ];

        $currency = $this->faker->randomElement($currencies);

        return [
            'name' => $currency['name'],
            'code' => $currency['code'],
            'symbol' => $currency['symbol'],
            'format' => $currency['format'],
        ];
    }
}
