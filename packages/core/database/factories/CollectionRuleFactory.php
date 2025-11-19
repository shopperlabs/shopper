<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Enum\Operator;
use Shopper\Core\Enum\Rule;
use Shopper\Core\Models\CollectionRule;

/**
 * @extends Factory<CollectionRule>
 */
class CollectionRuleFactory extends Factory
{
    protected $model = CollectionRule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rule' => $this->faker->randomElement(Rule::cases()),
            'operator' => $this->faker->randomElement(Operator::cases()),
            'value' => $this->faker->word(),
        ];
    }
}
