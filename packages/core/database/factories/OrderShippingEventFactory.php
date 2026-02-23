<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\OrderShipping;
use Shopper\Core\Models\OrderShippingEvent;

/**
 * @extends Factory<OrderShippingEvent>
 */
class OrderShippingEventFactory extends Factory
{
    protected $model = OrderShippingEvent::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(ShipmentStatus::cases()),
            'description' => $this->faker->optional()->sentence(),
            'location' => $this->faker->optional()->city(),
            'occurred_at' => $this->faker->dateTimeBetween('-7 days'),
            'order_shipping_id' => OrderShipping::factory(),
        ];
    }
}
