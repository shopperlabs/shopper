<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Price;
use Shopper\Core\Models\Product;

/**
 * @extends Factory<Price>
 */
class PriceFactory extends Factory
{
    protected $model = Price::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'priceable_type' => Product::class,
            'priceable_id' => Product::factory(),
            'amount' => $this->faker->numberBetween(10000, 50000),
            'compare_amount' => $this->faker->numberBetween(8000, 40000),
            'cost_amount' => $this->faker->numberBetween(5000, 20000),
            'currency_id' => Currency::factory(),
        ];
    }
}
