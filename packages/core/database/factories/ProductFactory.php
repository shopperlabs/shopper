<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $name = $this->faker->unique()->title(),
            'slug' => Str::slug($name),
            'sku' => $this->faker->unique()->ean8(),
            'barcode' => $this->faker->ean13(),
            'description' => $this->faker->realText(),
            'security_stock' => $this->faker->randomDigitNotNull(),
            'featured' => $this->faker->boolean(),
            'is_visible' => $this->faker->boolean(),
            'old_price_amount' => $this->faker->randomFloat(2, 100, 500),
            'price_amount' => $this->faker->randomFloat(2, 80, 400),
            'cost_amount' => $this->faker->randomFloat(2, 50, 200),
            'type' => $this->faker->randomElement(ProductType::names()),
            'published_at' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month'),
        ];
    }
}
