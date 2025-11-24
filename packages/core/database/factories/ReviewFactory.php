<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\Review;
use Tests\Core\Stubs\User;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating' => $this->faker->numberBetween(1, 5),
            'reviewrateable_type' => Product::class,
            'reviewrateable_id' => Product::factory(),
            'author_type' => User::class,
            'author_id' => User::factory(),
        ];
    }
}
