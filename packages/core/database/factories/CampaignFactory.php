<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Enum\CampaignBudgetType;
use Shopper\Core\Models\Campaign;

/**
 * @extends Factory<Campaign>
 */
class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'is_active' => true,
            'name' => $this->faker->unique()->words(2, true),
            'currency_code' => 'USD',
            'budget_type' => CampaignBudgetType::None->value,
            'spent_amount' => 0,
            'used_count' => 0,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ];
    }

    public function withSpendBudget(int $amount): static
    {
        return $this->state(fn (array $attributes): array => [
            'budget_type' => CampaignBudgetType::Spend->value,
            'budget_amount' => $amount,
        ]);
    }

    public function withCountBudget(int $count): static
    {
        return $this->state(fn (array $attributes): array => [
            'budget_type' => CampaignBudgetType::Count->value,
            'budget_count' => $count,
        ]);
    }
}
