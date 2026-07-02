<?php

declare(strict_types=1);

use Shopper\Core\Events\Orders\OrderCancelled;
use Shopper\Core\Listeners\Orders\RestoreOrderStockListener;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\StockLevel;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    $this->inventory = Inventory::factory()->create(['is_default' => true, 'priority' => 0]);
    $this->product = Product::factory()->standard()->create();
});

describe('Stock levels snapshot', function (): void {
    it('keeps the snapshot in sync with every ledger mutation', function (): void {
        $this->product->mutateStock($this->inventory->id, 10, event: 'Initial');
        $this->product->decreaseStock($this->inventory->id, 3);
        $this->product->mutateStock($this->inventory->id, 5);

        $level = StockLevel::query()
            ->where('stockable_type', $this->product->getMorphClass())
            ->where('stockable_id', $this->product->id)
            ->where('inventory_id', $this->inventory->id)
            ->sole();

        expect($level->quantity)->toBe(12)
            ->and($this->product->getStock())->toBe(12)
            ->and($this->product->stockInventory($this->inventory->id))->toBe(12);
    });

    it('reads the current stock without touching the ledger', function (): void {
        $this->product->mutateStock($this->inventory->id, 10, event: 'Initial');

        $this->product->inventoryHistories()->delete();

        expect($this->product->getStock())->toBe(10);
    });

    it('still replays the ledger for point-in-time queries', function (): void {
        $this->product->mutateStock($this->inventory->id, 10, event: 'Initial');
        $this->product->inventoryHistories()->update(['created_at' => now()->subDays(10)]);
        $this->product->decreaseStock($this->inventory->id, 4);

        expect($this->product->getStock(now()->subDays(5)->toDateTimeString()))->toBe(10)
            ->and($this->product->getStock())->toBe(6);
    });

    it('tracks one snapshot row per inventory location', function (): void {
        $secondInventory = Inventory::factory()->create(['priority' => 1]);

        $this->product->mutateStock($this->inventory->id, 10);
        $this->product->mutateStock($secondInventory->id, 7);

        expect($this->product->stockInventory($this->inventory->id))->toBe(10)
            ->and($this->product->stockInventory($secondInventory->id))->toBe(7)
            ->and($this->product->getStock())->toBe(17);
    });

    it('clears the snapshot together with the ledger', function (): void {
        $this->product->mutateStock($this->inventory->id, 10);

        $this->product->clearStock();

        expect(StockLevel::query()->where('stockable_id', $this->product->id)->count())->toBe(0)
            ->and($this->product->getStock())->toBe(0);
    });

    it('batch-loads the current stock from the snapshot', function (): void {
        $other = Product::factory()->standard()->create();
        $this->product->mutateStock($this->inventory->id, 10);
        $other->mutateStock($this->inventory->id, 4);

        $models = Product::query()->whereIn('id', [$this->product->id, $other->id])->get();
        Product::loadCurrentStock($models);

        expect($models->firstWhere('id', $this->product->id)->stock)->toBe(10)
            ->and($models->firstWhere('id', $other->id)->stock)->toBe(4);
    });
});

describe('Stock reconciliation', function (): void {
    it('reports and fixes a drifted snapshot from the ledger', function (): void {
        $this->product->mutateStock($this->inventory->id, 10, event: 'Initial');

        StockLevel::query()
            ->where('stockable_id', $this->product->id)
            ->update(['quantity' => 99]);

        $this->artisan('shopper:stock:reconcile')->assertFailed();
        $this->artisan('shopper:stock:reconcile', ['--fix' => true])->assertSuccessful();

        expect($this->product->getStock())->toBe(10);

        $this->artisan('shopper:stock:reconcile')->assertSuccessful();
    });
});

describe('Stock restore idempotence', function (): void {
    it('restores the stock only once when the listener runs twice', function (): void {
        $this->product->mutateStock($this->inventory->id, 10, event: 'Initial');

        $order = Order::factory()->create();
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_type' => $this->product->getMorphClass(),
            'product_id' => $this->product->id,
            'quantity' => 3,
        ]);

        resolve(Shopper\Core\Contracts\StockReserver::class)->reserve($this->product, 3, $order);
        expect($this->product->getStock())->toBe(7);

        $listener = new RestoreOrderStockListener;
        $listener->handle(new OrderCancelled($order));
        $listener->handle(new OrderCancelled($order));

        expect($this->product->refresh()->getStock())->toBe(10);
    });
});
