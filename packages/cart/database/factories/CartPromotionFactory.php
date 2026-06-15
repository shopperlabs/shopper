<?php

declare(strict_types=1);

namespace Shopper\Cart\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartPromotion;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Models\Discount;

/**
 * @extends Factory<CartPromotion>
 */
class CartPromotionFactory extends Factory
{
    protected $model = CartPromotion::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cart_id' => Cart::factory(),
            'discount_id' => Discount::factory(),
            'source' => PromotionSource::Code->value,
            'code' => mb_strtoupper($this->faker->bothify('PROMO###')),
            'computed_amount' => 0,
            'sequence' => 0,
        ];
    }

    public function automatic(): static
    {
        return $this->state(fn (array $attributes): array => [
            'source' => PromotionSource::Automatic->value,
            'code' => null,
        ]);
    }
}
