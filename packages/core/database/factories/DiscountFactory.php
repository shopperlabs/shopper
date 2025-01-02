<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Discount;

/**
 * @extends Factory<Discount>
 */
class DiscountFactory extends Factory
{
    protected $model = Discount::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->word(),
            'is_active' => $this->faker->boolean(),
            'type' => $this->faker->randomElement(DiscountType::values()),
            'value' => $this->faker->numberBetween(10, 1000),
            'apply_to' => $this->faker->randomElement(DiscountApplyTo::values()),
            'min_required' => $this->faker->randomElement(DiscountRequirement::values()),
            'eligibility' => $this->faker->randomElement(DiscountEligibility::values()),
            'start_at' => now(),
            'end_at' => now()->addMonth(),
        ];
    }

    public function forProduct(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'apply_to' => DiscountApplyTo::Products(),
        ]);
    }

    public function forOrder(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'apply_to' => DiscountApplyTo::Order(),
        ]);
    }
}
