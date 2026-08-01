<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Category;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function modelName(): string
    {
        return config('shopper.models.category', Category::class);
    }

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
            'name' => $name,
            'description' => $this->faker->realText(),
            'is_enabled' => true,
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month'),
        ];
    }

    public function disabled(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_enabled' => false,
        ]);
    }
}
