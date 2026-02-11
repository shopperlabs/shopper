<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\ProductTag;

/**
 * @extends Factory<ProductTag>
 */
class ProductTagFactory extends Factory
{
    protected $model = ProductTag::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
