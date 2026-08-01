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

    public function modelName(): string
    {
        return config('shopper.models.product', Product::class);
    }

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
            'featured' => false,
            'is_visible' => true,
            'type' => ProductType::Standard(),
            'published_at' => $this->faker->dateTimeBetween('-6 month'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month'),
        ];
    }

    public function publish(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible' => true,
            'published_at' => now(),
        ]);
    }

    public function unpublished(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible' => false,
            'published_at' => now()->addMonth(),
        ]);
    }

    public function featured(): self
    {
        return $this->state(fn (array $attributes): array => [
            'featured' => true,
        ]);
    }

    public function variant(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible' => true,
            'published_at' => now(),
            'type' => ProductType::Variant(),
        ]);
    }

    public function virtual(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible' => true,
            'published_at' => now(),
            'type' => ProductType::Virtual(),
        ]);
    }

    public function external(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible' => true,
            'published_at' => now(),
            'type' => ProductType::External(),
        ]);
    }

    public function standard(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible' => true,
            'published_at' => now(),
            'type' => ProductType::Standard(),
        ]);
    }

    public function configure(): static
    {
        $this->afterCreating = $this->afterCreating->concat([
            function (Product $product): void {
                $product->loadMissing('prices');
            },
        ]);

        return $this;
    }
}
