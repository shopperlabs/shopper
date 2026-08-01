<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Brand;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function modelName(): string
    {
        return config('shopper.models.brand', Brand::class);
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name = $this->faker->unique()->company(),
            'website' => 'https://www.'.$this->faker->domainName(),
            'description' => $this->faker->realText(),
            'is_enabled' => true,
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }

    public function disabled(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_enabled' => false,
        ]);
    }
}
