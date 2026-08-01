<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Supplier;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'email' => $this->faker->unique()->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'contact_name' => $this->faker->name(),
            'website' => 'https://www.'.$this->faker->domainName(),
            'description' => $this->faker->realText(),
            'is_enabled' => true,
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }

    public function disabled(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_enabled' => false,
        ]);
    }
}
