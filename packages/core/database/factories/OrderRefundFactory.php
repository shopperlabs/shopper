<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Enum\OrderRefundStatus;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderRefund;

/**
 * @extends Factory<OrderRefund>
 */
class OrderRefundFactory extends Factory
{
    protected $model = OrderRefund::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->numberBetween(1000, 10000),
            'currency' => 'EUR',
            'status' => $this->faker->randomElement(OrderRefundStatus::cases()),
            'order_id' => Order::factory(),
        ];
    }
}
