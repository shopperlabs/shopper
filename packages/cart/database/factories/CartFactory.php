<?php

declare(strict_types=1);

namespace Shopper\Cart\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Cart\Models\Cart;

/**
 * @extends Factory<Cart>
 */
class CartFactory extends Factory
{
    protected $model = Cart::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'currency_code' => 'USD',
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'completed_at' => now(),
        ]);
    }
}
