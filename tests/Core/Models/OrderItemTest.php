<?php

declare(strict_types=1);

use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\Product;

uses(Tests\Core\TestCase::class);

describe(OrderItem::class, function (): void {
    it('belongs to order', function (): void {
        $order = Order::factory()->create();
        $orderItem = OrderItem::factory()->create(['order_id' => $order->id]);

        expect($orderItem->order->id)->toBe($order->id);
    });

    it('has morphable product relationship', function (): void {
        $product = Product::factory()->create();
        $order = Order::factory()->create();
        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_type' => $product->getMorphClass(),
        ]);

        expect($orderItem->product)->toBeInstanceOf(Product::class)
            ->and($orderItem->product->id)->toBe($product->id);
    });

    it('calculates total from unit price and quantity', function (): void {
        $order = Order::factory()->create();
        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'unit_price_amount' => 100,
            'quantity' => 3,
        ]);

        expect($orderItem->total)->toBe(300);
    });
})->group('order', 'models');
