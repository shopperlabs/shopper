<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Zone;

/**
 * @extends Factory<CarrierOption>
 */
class CarrierOptionFactory extends Factory
{
    protected $model = CarrierOption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var string $name */
        $name = $this->faker->unique()->words(3, true);

        return [
            'name' => $name.' Option',
            'price' => $this->faker->numberBetween(500, 5000),
            'carrier_id' => Carrier::factory(),
            'zone_id' => Zone::factory(),
        ];
    }
}
