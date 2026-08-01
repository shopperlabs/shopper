<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Legal;

/**
 * @extends Factory<Legal>
 */
class LegalFactory extends Factory
{
    protected $model = Legal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => $title,
            'content' => '<p>'.$this->faker->paragraph().'</p>',
            'is_enabled' => true,
        ];
    }

    public function disabled(): self
    {
        return $this->state(fn (): array => ['is_enabled' => false]);
    }
}
