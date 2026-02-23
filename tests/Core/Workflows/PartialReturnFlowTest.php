<?php

declare(strict_types=1);

use Shopper\Core\Actions\MarkShipmentDeliveredAction;
use Shopper\Core\Actions\RecordShipmentEventAction;
use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\OrderShipping;

uses(Tests\TestCase::class);

describe('Partial return — one package delivered, one returned', function (): void {
    it('sets order to partially returned when one shipment is delivered and another returned', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::Shipped,
        ]);

        $shipment1 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::OutForDelivery,
            'shipped_at' => now()->subDays(3),
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment1->id,
            'fulfillment_status' => FulfillmentStatus::Shipped,
        ]);

        $shipment2 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::InTransit,
            'shipped_at' => now()->subDays(2),
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment2->id,
            'fulfillment_status' => FulfillmentStatus::Shipped,
        ]);

        (new MarkShipmentDeliveredAction)->execute($shipment1);

        expect($order->refresh()->shipping_status)->toBe(ShippingStatus::PartiallyDelivered);

        (new RecordShipmentEventAction)->execute($shipment2->refresh(), ShipmentStatus::Returned, [
            'description' => 'Customer refused package',
            'occurred_at' => now(),
        ]);

        $order->refresh();

        expect($order->shipping_status)->toBe(ShippingStatus::PartiallyReturned);
    });

    it('does not auto-complete order when some items are returned', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::PartiallyDelivered,
        ]);

        $shipment1 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::Delivered,
            'shipped_at' => now()->subDays(4),
            'received_at' => now()->subDay(),
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment1->id,
            'fulfillment_status' => FulfillmentStatus::Delivered,
        ]);

        $shipment2 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::InTransit,
            'shipped_at' => now()->subDays(2),
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment2->id,
            'fulfillment_status' => FulfillmentStatus::Shipped,
        ]);

        (new RecordShipmentEventAction)->execute($shipment2, ShipmentStatus::Returned, [
            'occurred_at' => now(),
        ]);

        $order->refresh();

        expect($order->shipping_status)->toBe(ShippingStatus::PartiallyReturned)
            ->and($order->status)->toBe(OrderStatus::Processing);
    });
})
    ->group('workflows', 'order-fulfillment');
