<?php

declare(strict_types=1);

use Shopper\Core\Contracts\StockReserver;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\Product;
use Tests\Core\Stubs\User;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->inventory = Inventory::factory()->create([
        'is_default' => true,
        'priority' => 0,
    ]);

    $this->product = Product::factory()->standard()->create();
    $this->product->mutateStock($this->inventory->id, 50, event: 'Initial');

    $this->order = Order::factory()->create([
        'customer_id' => $this->user->id,
        'status' => OrderStatus::New,
        'payment_status' => PaymentStatus::Pending,
        'shipping_status' => ShippingStatus::Unfulfilled,
    ]);

    $this->reserver = resolve(StockReserver::class);
});

describe('StockReservationTest', function (): void {
    it('decrements stock and returns the reserved quantity', function (): void {
        $reserved = $this->reserver->reserve($this->product, 3, $this->order, $this->user->id);

        expect($reserved)->toBe(3)
            ->and($this->product->getStock())->toBe(47);
    });

    it('creates an `InventoryHistory` with order reference', function (): void {
        $this->reserver->reserve($this->product, 5, $this->order, $this->user->id);

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

    it('reserves stock independently for each product', function (): void {
        $productA = Product::factory()->standard()->create();
        $productA->mutateStock($this->inventory->id, 20, event: 'Initial');

        $productB = Product::factory()->standard()->create();
        $productB->mutateStock($this->inventory->id, 10, event: 'Initial');

        $this->reserver->reserve($productA, 4, $this->order, $this->user->id);
        $this->reserver->reserve($productB, 7, $this->order, $this->user->id);

        expect($productA->getStock())->toBe(16)
            ->and($productB->getStock())->toBe(3);
    });

    it('returns the available quantity without decrementing when stock is insufficient and backorder is disabled', function (): void {
        $reserved = $this->reserver->reserve($this->product, 60, $this->order, $this->user->id);

        expect($reserved)->toBe(50)
            ->and($this->product->getStock())->toBe(50);
    });

    it('reserves the full quantity and goes negative when backorder is enabled', function (): void {
        $product = Product::factory()->standard()->create(['allow_backorder' => true]);
        $product->mutateStock($this->inventory->id, 2, event: 'Initial');

        $reserved = $this->reserver->reserve($product, 5, $this->order, $this->user->id);

        expect($reserved)->toBe(5)
            ->and($product->getStock())->toBe(-3);
    });

    it('returns zero and writes nothing when no inventory exists', function (): void {
        Inventory::query()->delete();

        $reserved = $this->reserver->reserve($this->product, 3, $this->order, $this->user->id);

        expect($reserved)->toBe(0);
    });
})->group('workflows', 'stock-allocation');
