<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Shopper\Core\Actions\MarkShipmentDeliveredAction;
use Shopper\Core\Actions\RecordShipmentEventAction;
use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Events\Orders\OrderShipmentDelivered;
use Shopper\Core\Events\Orders\OrderShipped;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\OrderShipping;

uses(Tests\TestCase::class);

describe('Partial shipment — multi-package delivery', function (): void {
    it('sets order to partially shipped when only some items are picked up', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        $shipment1 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::Pending,
            'shipped_at' => now(),
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment1->id,
            'fulfillment_status' => FulfillmentStatus::Pending,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'fulfillment_status' => FulfillmentStatus::Pending,
        ]);

        (new RecordShipmentEventAction)->execute($shipment1, ShipmentStatus::PickedUp, [
            'occurred_at' => now(),
        ]);

        $order->refresh();

        expect($order->shipping_status)->toBe(ShippingStatus::PartiallyShipped);
    });

    it('sets order to shipped when second package is also picked up', function (): void {
        Event::fake([OrderShipped::class]);

        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::PartiallyShipped,
        ]);

        $shipment1 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::InTransit,
            'shipped_at' => now()->subDay(),
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment1->id,
            'fulfillment_status' => FulfillmentStatus::Shipped,
        ]);

        $shipment2 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::Pending,
            'shipped_at' => now(),
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment2->id,
            'fulfillment_status' => FulfillmentStatus::Pending,
        ]);

        (new RecordShipmentEventAction)->execute($shipment2, ShipmentStatus::PickedUp, [
            'occurred_at' => now(),
        ]);

        $order->refresh();

        expect($order->shipping_status)->toBe(ShippingStatus::Shipped);

        Event::assertDispatched(OrderShipped::class, 1);
    });

    it('sets order to partially delivered when first package arrives', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::Shipped,
        ]);

        $shipment1 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::OutForDelivery,
            'shipped_at' => now()->subDays(2),
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment1->id,
            'fulfillment_status' => FulfillmentStatus::Shipped,
        ]);

        $shipment2 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::InTransit,
            'shipped_at' => now()->subDay(),
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment2->id,
            'fulfillment_status' => FulfillmentStatus::Shipped,
        ]);

        (new MarkShipmentDeliveredAction)->execute($shipment1);

        $order->refresh();

        expect($order->shipping_status)->toBe(ShippingStatus::PartiallyDelivered)
            ->and($order->status)->toBe(OrderStatus::Processing);
    });

    it('completes order when last package is delivered', function (): void {
        Event::fake([OrderShipmentDelivered::class]);

        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::PartiallyDelivered,
        ]);

        $shipment1 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::Delivered,
            'shipped_at' => now()->subDays(3),
            'received_at' => now()->subDay(),
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment1->id,
            'fulfillment_status' => FulfillmentStatus::Delivered,
        ]);

        $shipment2 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::OutForDelivery,
            'shipped_at' => now()->subDays(2),
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment2->id,
            'fulfillment_status' => FulfillmentStatus::Shipped,
        ]);

        (new MarkShipmentDeliveredAction)->execute($shipment2);

        $order->refresh();

        expect($order->shipping_status)->toBe(ShippingStatus::Delivered)
            ->and($order->status)->toBe(OrderStatus::Completed);

        Event::assertDispatched(OrderShipmentDelivered::class, 1);
    });

    it('handles full multi-package flow end-to-end', function (): void {
        Event::fake([OrderShipped::class, OrderShipmentDelivered::class]);

        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        $shipment1 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::Pending,
            'shipped_at' => now(),
        ]);

        $items12 = OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment1->id,
            'fulfillment_status' => FulfillmentStatus::Pending,
        ]);

        $shipment2 = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::Pending,
            'shipped_at' => now(),
        ]);

        $item3 = OrderItem::factory()->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment2->id,
            'fulfillment_status' => FulfillmentStatus::Pending,
        ]);

        (new RecordShipmentEventAction)->execute($shipment1, ShipmentStatus::PickedUp, [
            'occurred_at' => now(),
        ]);

        expect($order->refresh()->shipping_status)->toBe(ShippingStatus::PartiallyShipped);

        (new RecordShipmentEventAction)->execute($shipment2->refresh(), ShipmentStatus::PickedUp, [
            'occurred_at' => now()->addHour(),
        ]);

        expect($order->refresh()->shipping_status)->toBe(ShippingStatus::Shipped);

        (new RecordShipmentEventAction)->execute($shipment1->refresh(), ShipmentStatus::InTransit);
        (new RecordShipmentEventAction)->execute($shipment1->refresh(), ShipmentStatus::OutForDelivery);
        (new MarkShipmentDeliveredAction)->execute($shipment1->refresh());

        expect($order->refresh()->shipping_status)->toBe(ShippingStatus::PartiallyDelivered);

        (new RecordShipmentEventAction)->execute($shipment2->refresh(), ShipmentStatus::InTransit);
        (new RecordShipmentEventAction)->execute($shipment2->refresh(), ShipmentStatus::OutForDelivery);
        (new MarkShipmentDeliveredAction)->execute($shipment2->refresh());

        $order->refresh();

        expect($order->shipping_status)->toBe(ShippingStatus::Delivered)
            ->and($order->status)->toBe(OrderStatus::Completed)
            ->and($order->items)->each(
                fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Delivered)
            );

        Event::assertDispatched(OrderShipped::class, 1);
        Event::assertDispatched(OrderShipmentDelivered::class, 2);
    });
})
    ->group('workflows', 'order-fulfillment');
