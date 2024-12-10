<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Price;

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
            'amount' => $this->faker->randomFloat(min: 100, max: 500),
            'compare_amount' => $this->faker->randomFloat(min: 80, max: 400),
            'cost_amount' => $this->faker->randomFloat(min: 50, max: 200),
        ];
    }
}
