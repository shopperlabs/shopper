<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;

/**
 * @extends Factory<Attribute>
 */
class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
            'type' => FieldType::Text(),
            'icon' => null,
            'is_enabled' => true,
            'is_searchable' => false,
            'is_filterable' => false,
        ];
    }

    public function disabled(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_enabled' => false,
        ]);
    }

    public function searchable(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_searchable' => true,
        ]);
    }

    public function filterable(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_filterable' => true,
        ]);
    }
}
