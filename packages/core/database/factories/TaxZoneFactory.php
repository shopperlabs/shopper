<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\TaxZone;

/**
 * @extends Factory<TaxZone>
 */
class TaxZoneFactory extends Factory
{
    protected $model = TaxZone::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country_id' => Country::query()->inRandomOrder()->value('id'),
            'is_tax_inclusive' => false,
        ];
    }

    public function inclusive(): static
    {
        return $this->state([
            'is_tax_inclusive' => true,
        ]);
    }
}
