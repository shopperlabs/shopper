<?php

declare(strict_types=1);

use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Events\Orders\OrderCancelled;
use Shopper\Core\Events\Orders\OrderItemCreated;
use Shopper\Core\Listeners\Orders\ReserveOrderItemStockListener;
use Shopper\Core\Listeners\Orders\RestoreOrderStockListener;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\Product;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    Event::fake([OrderItemCreated::class]);

    $this->inventory = Inventory::factory()->create([
        'is_default' => true,
        'priority' => 0,
    ]);

    $this->product = Product::factory()->standard()->create();
    $this->product->mutateStock($this->inventory->id, 50, [
        'event' => 'Initial',
        'old_quantity' => 0,
    ]);

    $this->order = Order::factory()->create([
        'customer_id' => $this->user->id,
        'status' => OrderStatus::New,
        'payment_status' => PaymentStatus::Pending,
        'shipping_status' => ShippingStatus::Unfulfilled,
    ]);
});

describe('StockRestorationTest', function (): void {
    it('restores stock when an order is cancelled', function (): void {
        $item = OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_type' => $this->product->getMorphClass(),
            'product_id' => $this->product->id,
            'quantity' => 8,
        ]);

        $reserveListener = resolve(ReserveOrderItemStockListener::class);
        $reserveListener->handle(new OrderItemCreated($item));

        expect($this->product->getStock())->toBe(42);

        $this->order->update(['status' => OrderStatus::Cancelled, 'cancelled_at' => now()]);

        $restoreListener = resolve(RestoreOrderStockListener::class);
        $restoreListener->handle(new OrderCancelled($this->order));

        expect($this->product->getStock())->toBe(50);
    });

    it('restores stock for all items in a cancelled order', function (): void {
        $productA = Product::factory()->standard()->create();
        $productA->mutateStock($this->inventory->id, 30, ['event' => 'Initial', 'old_quantity' => 0]);

        $productB = Product::factory()->standard()->create();
        $productB->mutateStock($this->inventory->id, 15, ['event' => 'Initial', 'old_quantity' => 0]);

        $itemA = OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_type' => $productA->getMorphClass(),
            'product_id' => $productA->id,
            'quantity' => 5,
        ]);

        $itemB = OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_type' => $productB->getMorphClass(),
            'product_id' => $productB->id,
            'quantity' => 10,
        ]);

        $reserveListener = resolve(ReserveOrderItemStockListener::class);
        $reserveListener->handle(new OrderItemCreated($itemA));
        $reserveListener->handle(new OrderItemCreated($itemB));

        expect($productA->getStock())->toBe(25)
            ->and($productB->getStock())->toBe(5);

        $this->order->update(['status' => OrderStatus::Cancelled, 'cancelled_at' => now()]);

        $restoreListener = resolve(RestoreOrderStockListener::class);
        $restoreListener->handle(new OrderCancelled($this->order));

        expect($productA->getStock())->toBe(30)
            ->and($productB->getStock())->toBe(15);
    });

    it('creates restoration `InventoryHistory` records with order reference', function (): void {
        $item = OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_type' => $this->product->getMorphClass(),
            'product_id' => $this->product->id,
            'quantity' => 6,
        ]);

        $reserveListener = resolve(ReserveOrderItemStockListener::class);
        $reserveListener->handle(new OrderItemCreated($item));

        $this->order->update(['status' => OrderStatus::Cancelled, 'cancelled_at' => now()]);

        $restoreListener = resolve(RestoreOrderStockListener::class);
        $restoreListener->handle(new OrderCancelled($this->order));

        $histories = $this->product->inventoryHistories()
            ->where('reference_type', $this->order->getMorphClass())
            ->where('reference_id', $this->order->id)
            ->orderBy('id')
            ->get();

        // One reservation (-6) + one restoration (+6)
        expect($histories)->toHaveCount(2)
            ->and($histories[0]->quantity)->toBe(-6)
            ->and($histories[0]->user_id)->toBe($this->user->id)
            ->and($histories[1]->quantity)->toBe(6)
            ->and($histories[1]->user_id)->toBe($this->user->id);
    });

    it('restores stock to the correct inventories after a split reservation is cancelled', function (): void {
        $lyon = Inventory::factory()->create(['priority' => 0, 'is_default' => true]);
        $paris = Inventory::factory()->create(['priority' => 1, 'is_default' => false]);

        $product = Product::factory()->standard()->create();
        $product->mutateStock($lyon->id, 2, ['event' => 'Initial', 'old_quantity' => 0]);
        $product->mutateStock($paris->id, 3, ['event' => 'Initial', 'old_quantity' => 0]);

        $order = Order::factory()->create([
            'customer_id' => $this->user->id,
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Pending,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        $item = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_type' => $product->getMorphClass(),
            'product_id' => $product->id,
            'quantity' => 4,
        ]);

        // Reserve — should split: Lyon=2, Paris=2
        $reserveListener = resolve(ReserveOrderItemStockListener::class);
        $reserveListener->handle(new OrderItemCreated($item));

        expect($product->stockInventory($lyon->id))->toBe(0)
            ->and($product->stockInventory($paris->id))->toBe(1);

        // Cancel — should restore to the same inventories
        $order->update(['status' => OrderStatus::Cancelled, 'cancelled_at' => now()]);

        $restoreListener = resolve(RestoreOrderStockListener::class);
        $restoreListener->handle(new OrderCancelled($order));

        expect($product->stockInventory($lyon->id))->toBe(2)
            ->and($product->stockInventory($paris->id))->toBe(3);
    });
})->group('workflows', 'stock-allocation');
