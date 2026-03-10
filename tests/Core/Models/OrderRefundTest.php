<?php

declare(strict_types=1);

use Shopper\Core\Enum\OrderRefundStatus;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderRefund;
use Tests\Core\Stubs\User;

uses(Tests\Core\TestCase::class);

describe(OrderRefund::class, function (): void {
    it('belongs to order', function (): void {
        $order = Order::factory()->create();
        $refund = OrderRefund::factory()->create(['order_id' => $order->id]);

        expect($refund->order->id)->toBe($order->id);
    });

    it('belongs to customer', function (): void {
        $user = User::factory()->create();
        $order = Order::factory()->create();
        $refund = OrderRefund::factory()->create([
            'order_id' => $order->id,
            'user_id' => $user->id,
        ]);

        expect($refund->customer->id)->toBe($user->id);
    });

    it('has status enum', function (): void {
        $order = Order::factory()->create();
        $refund = OrderRefund::factory()->create([
            'order_id' => $order->id,
            'status' => OrderRefundStatus::Pending,
        ]);

        expect($refund->status)->toBe(OrderRefundStatus::Pending)
            ->and($refund->status->getLabel())->toBe(__('shopper-core::status.pending'));
    });
})->group('order', 'models');
