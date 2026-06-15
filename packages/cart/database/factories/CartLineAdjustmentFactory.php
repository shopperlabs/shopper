<?php

declare(strict_types=1);

namespace Shopper\Cart\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Cart\Models\CartLine;
use Shopper\Cart\Models\CartLineAdjustment;

/**
 * @extends Factory<CartLineAdjustment>
 */
class CartLineAdjustmentFactory extends Factory
{
    protected $model = CartLineAdjustment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cart_line_id' => CartLine::factory(),
            'amount' => $this->faker->numberBetween(100, 5_000),
            'code' => mb_strtoupper($this->faker->bothify('SAVE##')),
        ];
    }
}
