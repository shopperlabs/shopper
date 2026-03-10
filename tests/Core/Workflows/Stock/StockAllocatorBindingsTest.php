<?php

declare(strict_types=1);

use Shopper\Core\Contracts\InventoryResolver;
use Shopper\Core\Contracts\StockAllocator;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Stock\DefaultInventoryResolver;
use Shopper\Core\Stock\PriorityStockAllocator;
use Tests\Core\Stubs\User;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    $this->actingAs(User::factory()->create());
});

describe('StockAllocatorBindingTest', function (): void {
    it('resolves `StockAllocator` from the container', function (): void {
        expect(resolve(StockAllocator::class))->toBeInstanceOf(PriorityStockAllocator::class);
    });

    it('resolves `InventoryResolver` from the container', function (): void {
        expect(resolve(InventoryResolver::class))->toBeInstanceOf(DefaultInventoryResolver::class);
    });

    it('returns all inventories sorted by priority via `DefaultInventoryResolver`', function (): void {
        Inventory::query()->delete();

        $paris = Inventory::factory()->create(['priority' => 2]);
        $lyon = Inventory::factory()->create(['priority' => 0]);
        $marseille = Inventory::factory()->create(['priority' => 1]);

        $product = Product::factory()->standard()->create();

        $resolver = resolve(InventoryResolver::class);
        $inventories = $resolver->resolve($product);

        expect($inventories)->toHaveCount(3)
            ->and($inventories[0]->id)->toBe($lyon->id)
            ->and($inventories[1]->id)->toBe($marseille->id)
            ->and($inventories[2]->id)->toBe($paris->id);
    });

    it('allows swapping the `InventoryResolver` via container binding', function (): void {
        $specificInventory = Inventory::factory()->create(['priority' => 0]);
        Inventory::factory()->create(['priority' => 1]);

        $this->app->bind(InventoryResolver::class, function () use ($specificInventory) {
            return new class($specificInventory->id) implements InventoryResolver
            {
                public function __construct(private readonly int $inventoryId) {}

                public function resolve(Shopper\Core\Models\Contracts\Stockable $product): Illuminate\Database\Eloquent\Collection
                {
                    return Inventory::query()->where('id', $this->inventoryId)->get();
                }
            };
        });

        $product = Product::factory()->standard()->create();
        $product->mutateStock($specificInventory->id, 20, event: 'Initial');

        $allocator = resolve(StockAllocator::class);
        $allocations = $allocator->allocate($product, 5);

        expect($allocations)->toHaveCount(1)
            ->and($allocations[0]->inventoryId)->toBe($specificInventory->id);
    });
})->group('workflows', 'stock-allocation');
