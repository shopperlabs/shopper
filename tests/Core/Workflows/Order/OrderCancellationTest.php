<?php

declare(strict_types=1);

use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Models\Order;

uses(Tests\Core\TestCase::class);

describe('Order cancellation rules', function (): void {
    it('allows cancellation when order is unfulfilled', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::New,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        expect($order->canBeCancelled())->toBeTrue();
    });

    it('allows cancellation for processing order with no shipments', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        expect($order->canBeCancelled())->toBeTrue();
    });

    it('blocks cancellation when items are shipped', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'shipping_status' => ShippingStatus::Shipped,
        ]);

        expect($order->canBeCancelled())->toBeFalse();
    });

    it('blocks cancellation when items are partially shipped', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'shipping_status' => ShippingStatus::PartiallyShipped,
        ]);

        expect($order->canBeCancelled())->toBeFalse();
    });

    it('blocks cancellation for completed orders', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Completed,
            'shipping_status' => ShippingStatus::Delivered,
        ]);

        expect($order->canBeCancelled())->toBeFalse();
    });

    it('blocks cancellation for already cancelled orders', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Cancelled,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        expect($order->canBeCancelled())->toBeFalse();
    });

    it('blocks cancellation for archived orders', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Archived,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        expect($order->canBeCancelled())->toBeFalse();
    });
})
    ->group('workflows', 'order-fulfillment');
