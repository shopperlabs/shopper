<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxZone;

/**
 * @extends Factory<TaxRate>
 */
class TaxRateFactory extends Factory
{
    protected $model = TaxRate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Tax '.$this->faker->randomFloat(1, 1, 25).'%',
            'rate' => $this->faker->randomFloat(4, 0, 25),
            'is_default' => true,
            'is_combinable' => false,
            'tax_zone_id' => TaxZone::factory(),
        ];
    }

    public function default(): static
    {
        return $this->state([
            'is_default' => true,
        ]);
    }

    public function combinable(): static
    {
        return $this->state([
            'is_combinable' => true,
        ]);
    }
}
