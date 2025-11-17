<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderShipping;

/**
 * @extends Factory<OrderShipping>
 */
class OrderShippingFactory extends Factory
{
    protected $model = OrderShipping::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shipped_at' => $this->faker->dateTimeBetween('-30 days'),
            'order_id' => Order::factory(),
        ];
    }
}
