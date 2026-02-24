<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxRateRule;

/**
 * @extends Factory<TaxRateRule>
 */
class TaxRateRuleFactory extends Factory
{
    protected $model = TaxRateRule::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference_type' => 'product_type',
            'reference_id' => 'standard',
            'tax_rate_id' => TaxRate::factory(),
        ];
    }
}
