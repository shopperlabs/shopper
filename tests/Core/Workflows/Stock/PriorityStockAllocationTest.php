<?php

declare(strict_types=1);

use Shopper\Core\Contracts\StockAllocator;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Tests\Core\Stubs\User;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    $this->actingAs(User::factory()->create());
});

describe('PriorityStockAllocation', function (): void {
    it('allocates from the highest-priority inventory that has enough stock', function (): void {
        $douala = Inventory::factory()->create(['priority' => 0, 'is_default' => true]);
        $paris = Inventory::factory()->create(['priority' => 1, 'is_default' => false]);

        $product = Product::factory()->standard()->create();
        $product->mutateStock($douala->id, 20, event: 'Initial');
        $product->mutateStock($paris->id, 30, event: 'Initial');

        $allocator = resolve(StockAllocator::class);
        $allocations = $allocator->allocate($product, 15);

        expect($allocations)->toHaveCount(1)
            ->and($allocations[0]->inventoryId)->toBe($douala->id)
            ->and($allocations[0]->quantity)->toBe(15);
    });

    it('skips priority inventory when it has insufficient stock', function (): void {
        $douala = Inventory::factory()->create(['priority' => 0, 'is_default' => true]);
        $paris = Inventory::factory()->create(['priority' => 1, 'is_default' => false]);

        $product = Product::factory()->standard()->create();
        $product->mutateStock($douala->id, 2, event: 'Initial');
        $product->mutateStock($paris->id, 30, event: 'Initial');

        $allocator = resolve(StockAllocator::class);
        $allocations = $allocator->allocate($product, 10);

        // Douala has only 2, can't fulfill 10 → takes from Paris (30)
        expect($allocations)->toHaveCount(1)
            ->and($allocations[0]->inventoryId)->toBe($paris->id)
            ->and($allocations[0]->quantity)->toBe(10);
    });

    it('splits when no single inventory can fulfill the full quantity', function (): void {
        $douala = Inventory::factory()->create(['priority' => 0, 'is_default' => true]);
        $paris = Inventory::factory()->create(['priority' => 1, 'is_default' => false]);

        $product = Product::factory()->standard()->create();
        $product->mutateStock($douala->id, 2, event: 'Initial');
        $product->mutateStock($paris->id, 3, event: 'Initial');

        $allocator = resolve(StockAllocator::class);
        $allocations = $allocator->allocate($product, 4);

        // Douala: 2, Paris: 3 — neither has 4 alone → split: Douala=2, Paris=2
        expect($allocations)->toHaveCount(2)
            ->and($allocations[0]->inventoryId)->toBe($douala->id)
            ->and($allocations[0]->quantity)->toBe(2)
            ->and($allocations[1]->inventoryId)->toBe($paris->id)
            ->and($allocations[1]->quantity)->toBe(2);
    });

    it('splits across three inventories respecting priority order', function (): void {
        $douala = Inventory::factory()->create(['priority' => 0, 'is_default' => true]);
        $paris = Inventory::factory()->create(['priority' => 1, 'is_default' => false]);
        $marseille = Inventory::factory()->create(['priority' => 2, 'is_default' => false]);

        $product = Product::factory()->standard()->create();
        $product->mutateStock($douala->id, 3, event: 'Initial');
        $product->mutateStock($paris->id, 4, event: 'Initial');
        $product->mutateStock($marseille->id, 5, event: 'Initial');

        $allocator = resolve(StockAllocator::class);
        $allocations = $allocator->allocate($product, 10);

        // None has 10 alone → split: Douala=3, Paris=4, Marseille=3
        expect($allocations)->toHaveCount(3)
            ->and($allocations[0]->inventoryId)->toBe($douala->id)
            ->and($allocations[0]->quantity)->toBe(3)
            ->and($allocations[1]->inventoryId)->toBe($paris->id)
            ->and($allocations[1]->quantity)->toBe(4)
            ->and($allocations[2]->inventoryId)->toBe($marseille->id)
            ->and($allocations[2]->quantity)->toBe(3);
    });

    it('returns partial allocations when total stock is less than requested', function (): void {
        $inventory = Inventory::factory()->create(['priority' => 0, 'is_default' => true]);

        $product = Product::factory()->standard()->create();
        $product->mutateStock($inventory->id, 3, event: 'Initial');

        $allocator = resolve(StockAllocator::class);
        $allocations = $allocator->allocate($product, 10);

        // Only 3 available, requested 10 → allocates what's available
        expect($allocations)->toHaveCount(1)
            ->and($allocations[0]->quantity)->toBe(3);
    });

    it('returns empty when no stock is available', function (): void {
        Inventory::factory()->create(['priority' => 0, 'is_default' => true]);

        $product = Product::factory()->standard()->create();

        $allocator = resolve(StockAllocator::class);
        $allocations = $allocator->allocate($product, 5);

        expect($allocations)->toBeEmpty();
    });

    it('skips inventories with zero stock during split', function (): void {
        $douala = Inventory::factory()->create(['priority' => 0, 'is_default' => true]);
        Inventory::factory()->create(['priority' => 1, 'is_default' => false]);
        $marseille = Inventory::factory()->create(['priority' => 2, 'is_default' => false]);

        $product = Product::factory()->standard()->create();
        $product->mutateStock($douala->id, 3, event: 'Initial');
        // Paris has 0 stock
        $product->mutateStock($marseille->id, 5, event: 'Initial');

        $allocator = resolve(StockAllocator::class);
        $allocations = $allocator->allocate($product, 6);

        // Douala=3, Paris skipped (0), Marseille=3
        expect($allocations)->toHaveCount(2)
            ->and($allocations[0]->inventoryId)->toBe($douala->id)
            ->and($allocations[0]->quantity)->toBe(3)
            ->and($allocations[1]->inventoryId)->toBe($marseille->id)
            ->and($allocations[1]->quantity)->toBe(3);
    });
})->group('workflows', 'stock-allocation');
