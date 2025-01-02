<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Product;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->title(),
            'slug' => $this->faker->unique()->slug(),
            'sku' => $this->faker->unique()->ean8(),
            'barcode' => $this->faker->ean13(),
            'description' => $this->faker->realText(),
            'security_stock' => $this->faker->randomDigitNotNull(),
            'featured' => $this->faker->boolean(),
            'is_visible' => $this->faker->boolean(),
            'type' => $this->faker->randomElement(ProductType::values()),
            'published_at' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month'),
        ];
    }

    public function publish(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_visible' => true,
            'published_at' => now(),
        ]);
    }

    public function variant(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_visible' => true,
            'published_at' => now(),
            'type' => ProductType::Variant(),
        ]);
    }

    public function virtual(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_visible' => true,
            'published_at' => now(),
            'type' => ProductType::Virtual(),
        ]);
    }

    public function external(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_visible' => true,
            'published_at' => now(),
            'type' => ProductType::External(),
        ]);
    }

    public function standard(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_visible' => true,
            'published_at' => now(),
            'type' => ProductType::Standard(),
        ]);
    }
}
