<?php

declare(strict_types=1);

use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderShipping;

uses(Tests\TestCase::class);

describe(OrderShipping::class, function (): void {
    it('belongs to order', function (): void {
        $order = Order::factory()->create();
        $shipping = OrderShipping::factory()->create(['order_id' => $order->id]);

        expect($shipping->order->id)->toBe($order->id);
    });

    it('belongs to carrier', function (): void {
        $carrier = Carrier::factory()->create(['slug' => 'test-carrier']);
        $order = Order::factory()->create();
        $shipping = OrderShipping::factory()->create([
            'order_id' => $order->id,
            'carrier_id' => $carrier->id,
        ]);

        expect($shipping->carrier->id)->toBe($carrier->id);
    });
})->group('order', 'models', 'shipping');
