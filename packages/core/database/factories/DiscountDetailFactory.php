<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\DiscountDetail;

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
        return [];
    }
}
