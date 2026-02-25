<?php

declare(strict_types=1);

use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Events\Orders\OrderItemCreated;
use Shopper\Core\Listeners\Orders\ReserveOrderItemStockListener;
use Shopper\Core\Models\Channel;
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

describe('StockReservationTest', function (): void {
    it('decrements stock when an `OrderItem` is created', function (): void {
        $item = OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_type' => $this->product->getMorphClass(),
            'product_id' => $this->product->id,
            'quantity' => 3,
        ]);

        $listener = resolve(ReserveOrderItemStockListener::class);
        $listener->handle(new OrderItemCreated($item));

        expect($this->product->getStock())->toBe(47);
    });

    it('creates an `InventoryHistory` with order reference', function (): void {
        $item = OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_type' => $this->product->getMorphClass(),
            'product_id' => $this->product->id,
            'quantity' => 5,
        ]);

        $listener = resolve(ReserveOrderItemStockListener::class);
        $listener->handle(new OrderItemCreated($item));

        $history = $this->product->inventoryHistories()
            ->where('reference_type', $this->order->getMorphClass())
            ->where('reference_id', $this->order->id)
            ->where('quantity', '<', 0)
            ->first();

        expect($history)->not->toBeNull()
            ->and($history->quantity)->toBe(-5)
            ->and($history->inventory_id)->toBe($this->inventory->id)
            ->and($history->user_id)->toBe($this->user->id);
    });

    it('reserves stock independently for each item in an order', function (): void {
        $productA = Product::factory()->standard()->create();
        $productA->mutateStock($this->inventory->id, 20, ['event' => 'Initial', 'old_quantity' => 0]);

        $productB = Product::factory()->standard()->create();
        $productB->mutateStock($this->inventory->id, 10, ['event' => 'Initial', 'old_quantity' => 0]);

        $itemA = OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_type' => $productA->getMorphClass(),
            'product_id' => $productA->id,
            'quantity' => 4,
        ]);

        $itemB = OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_type' => $productB->getMorphClass(),
            'product_id' => $productB->id,
            'quantity' => 7,
        ]);

        $listener = resolve(ReserveOrderItemStockListener::class);
        $listener->handle(new OrderItemCreated($itemA));
        $listener->handle(new OrderItemCreated($itemB));

        expect($productA->getStock())->toBe(16)
            ->and($productB->getStock())->toBe(3);
    });

    it('skips stock reservation for non-stockable products', function (): void {
        $channel = Channel::factory()->create();

        $item = OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_type' => $channel->getMorphClass(),
            'product_id' => $channel->id,
            'quantity' => 2,
        ]);

        $listener = resolve(ReserveOrderItemStockListener::class);
        $listener->handle(new OrderItemCreated($item));

        expect($this->product->getStock())->toBe(50);
    });

    it('handles reservation when no inventory exists', function (): void {
        Inventory::query()->delete();

        $item = OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_type' => $this->product->getMorphClass(),
            'product_id' => $this->product->id,
            'quantity' => 3,
        ]);

        $listener = resolve(ReserveOrderItemStockListener::class);
        $listener->handle(new OrderItemCreated($item));

        // Should not throw, just returns empty allocations
        expect(true)->toBeTrue();
    });
})->group('workflows', 'stock-allocation');
