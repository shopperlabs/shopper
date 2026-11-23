<?php

declare(strict_types=1);

use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\InventoryHistory;
use Shopper\Core\Models\Product;
use Tests\Core\Stubs\User;

uses(Tests\Core\TestCase::class);

describe(InventoryHistory::class, function (): void {
    it('belongs to inventory', function (): void {
        $inventory = Inventory::factory()->create();
        $user = User::factory()->create();
        $history = InventoryHistory::factory()->create([
            'inventory_id' => $inventory->id,
            'user_id' => $user->id,
        ]);

        expect($history->inventory->id)->toBe($inventory->id);
    });

    it('belongs to user', function (): void {
        $inventory = Inventory::factory()->create();
        $user = User::factory()->create();
        $history = InventoryHistory::factory()->create([
            'inventory_id' => $inventory->id,
            'user_id' => $user->id,
        ]);

        expect($history->user->id)->toBe($user->id);
    });

    it('has stockable relationship', function (): void {
        $inventory = Inventory::factory()->create();
        $user = User::factory()->create();
        $history = InventoryHistory::factory()->create([
            'inventory_id' => $inventory->id,
            'user_id' => $user->id,
        ]);

        expect($history->stockable())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\MorphTo::class);
    });

    it('has reference relationship', function (): void {
        $inventory = Inventory::factory()->create();
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $history = InventoryHistory::factory()->create([
            'inventory_id' => $inventory->id,
            'user_id' => $user->id,
            'reference_type' => $product->getMorphClass(),
            'reference_id' => $product->id,
        ]);

        expect($history->reference())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\MorphTo::class)
            ->and($history->reference)->toBeInstanceOf(Product::class)
            ->and($history->reference->id)->toBe($product->id);
    });

    it('computes the stock once the movement is applied', function (): void {
        $history = InventoryHistory::factory()->create([
            'inventory_id' => Inventory::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'old_quantity' => 10,
            'quantity' => -3,
        ]);

        expect($history->stock_after)->toBe(7);
    });

    it('recomputes the stock before each movement from the ledger', function (): void {
        $paris = Inventory::factory()->create();
        $lyon = Inventory::factory()->create();
        $tee = Product::factory()->create();
        $mug = Product::factory()->create();

        $movement = fn (Product $product, Inventory $inventory, int $quantity, int $oldQuantity): InventoryHistory => InventoryHistory::factory()->create([
            'stockable_type' => $product->getMorphClass(),
            'stockable_id' => $product->id,
            'inventory_id' => $inventory->id,
            'quantity' => $quantity,
            'old_quantity' => $oldQuantity,
        ]);

        $movements = [
            [$movement($tee, $paris, 10, 10), 10],
            [$movement($tee, $paris, 5, 5), 15],
            [$movement($tee, $lyon, 3, 3), 3],
            [$movement($tee, $paris, -3, -3), 12],
            [$movement($mug, $paris, 4, 0), 4],
            [$movement($mug, $paris, 2, 0), 6],
        ];

        $migration = require dirname(__DIR__, 3).'/packages/core/database/migrations/2026_11_23_000002_recompute_old_quantity_of_inventory_movements.php';
        $migration->up();

        foreach ($movements as [$history, $stockAfter]) {
            expect($history->fresh()->stock_after)->toBe($stockAfter);
        }
    });
})->group('inventory', 'models');
