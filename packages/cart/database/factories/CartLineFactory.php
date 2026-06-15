<?php

declare(strict_types=1);

namespace Shopper\Cart\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;
use Shopper\Core\Models\Product;

/**
 * @extends Factory<CartLine>
 */
class CartLineFactory extends Factory
{
    protected $model = CartLine::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cart_id' => Cart::factory(),
            'purchasable_type' => (new Product)->getMorphClass(),
            'purchasable_id' => Product::factory(),
            'quantity' => 1,
            'unit_price_amount' => $this->faker->numberBetween(500, 50_000),
        ];
    }
}
