<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Channel;

/**
 * @extends Factory<Channel>
 */
class ChannelFactory extends Factory
{
    protected $model = Channel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'timezone' => $this->faker->timezone(),
            'url' => $this->faker->url(),
            'is_enabled' => true,
            'is_default' => false,
        ];
    }

    public function disabled(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_enabled' => false,
        ]);
    }
}
