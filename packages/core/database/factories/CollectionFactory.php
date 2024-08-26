<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Collection;

class CollectionFactory extends Factory
{
    protected $model = Collection::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'slug' => $this->faker->unique()->slug(),
            'type' => $this->faker->randomElement(CollectionType::values()),
            'description' => $this->faker->text(),
        ];
    }
}
