<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;
use Shopper\Core\Models\Product;

/**
 * @extends Factory<DiscountDetail>
 */
class DiscountDetailFactory extends Factory
{
    protected $model = DiscountDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'discountable_type' => Product::class,
            'discountable_id' => Product::factory(),
            'discount_id' => Discount::factory(),
        ];
    }
}
