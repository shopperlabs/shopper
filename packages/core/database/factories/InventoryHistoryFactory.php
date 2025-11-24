<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\InventoryHistory;
use Shopper\Core\Models\Product;
use Tests\Core\Stubs\User;

/**
 * @extends Factory<InventoryHistory>
 */
class InventoryHistoryFactory extends Factory
{
    protected $model = InventoryHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stockable_type' => Product::class,
            'stockable_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'inventory_id' => Inventory::factory(),
            'user_id' => User::factory(),
        ];
    }
}
