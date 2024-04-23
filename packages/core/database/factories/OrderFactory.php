<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Order;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'number' => '#SH' . $this->faker->unique()->randomNumber(6),
            'currency' => strtolower(shopper_currency()),
            'total_price' => $this->faker->randomFloat(2, 100, 2000),
            'status' => $this->faker->randomElement(['new', 'processing', 'shipped', 'delivered', 'cancelled']),
            'shipping_price' => $this->faker->randomFloat(2, 100, 500),
            'shipping_method' => $this->faker->randomElement(['free', 'flat', 'flat_rate', 'flat_rate_per_item']),
            'notes' => $this->faker->realText(100),
            'created_at' => $this->faker->dateTimeBetween('-1 year'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month'),
        ];
    }
}
