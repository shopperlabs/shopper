<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\TaxProvider;

/**
 * @extends Factory<TaxProvider>
 */
class TaxProviderFactory extends Factory
{
    protected $model = TaxProvider::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'identifier' => $this->faker->unique()->slug(2),
            'is_enabled' => true,
        ];
    }
}
