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

    it('calculates adjustment for positive old_quantity', function (): void {
        $inventory = Inventory::factory()->create();
        $user = User::factory()->create();

        $history = InventoryHistory::factory()->create([
            'inventory_id' => $inventory->id,
            'user_id' => $user->id,
            'old_quantity' => 10,
        ]);

        expect($history->adjustment)->toBe('+10');
    });

    it('calculates adjustment for zero or negative old_quantity', function (): void {
        $inventory = Inventory::factory()->create();
        $user = User::factory()->create();

        $historyZero = InventoryHistory::factory()->create([
            'inventory_id' => $inventory->id,
            'user_id' => $user->id,
            'old_quantity' => 0,
        ]);

        $historyNegative = InventoryHistory::factory()->create([
            'inventory_id' => $inventory->id,
            'user_id' => $user->id,
            'old_quantity' => -5,
        ]);

        expect($historyZero->adjustment)->toBe(0)
            ->and($historyNegative->adjustment)->toBe(-5);
    });
})->group('inventory', 'models');
