<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Shopper\Core\Actions\MarkShipmentDeliveredAction;
use Shopper\Core\Actions\RecordShipmentEventAction;
use Shopper\Core\Actions\SyncOrderShippingStatusAction;
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

describe('Fulfillment event dispatch correctness', function (): void {
    it('dispatches OrderShipped only once when status transitions to shipped', function (): void {
        Event::fake([OrderShipped::class]);

        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        $shipment = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::Pending,
            'shipped_at' => now(),
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment->id,
            'fulfillment_status' => FulfillmentStatus::Pending,
        ]);

        (new RecordShipmentEventAction)->execute($shipment, ShipmentStatus::PickedUp, [
            'occurred_at' => now(),
        ]);

        Event::assertDispatched(OrderShipped::class, 1);

        (new SyncOrderShippingStatusAction)->execute($order->refresh());

        Event::assertDispatched(OrderShipped::class, 1);
    });

    it('does not dispatch OrderShipped for partially shipped orders', function (): void {
        Event::fake([OrderShipped::class]);

        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        $shipment = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::Pending,
            'shipped_at' => now(),
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment->id,
            'fulfillment_status' => FulfillmentStatus::Pending,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'fulfillment_status' => FulfillmentStatus::Pending,
        ]);

        (new RecordShipmentEventAction)->execute($shipment, ShipmentStatus::PickedUp, [
            'occurred_at' => now(),
        ]);

        $order->refresh();

        expect($order->shipping_status)->toBe(ShippingStatus::PartiallyShipped);

        Event::assertNotDispatched(OrderShipped::class);
    });

    it('dispatches OrderShipmentDelivered with correct shipment payload', function (): void {
        Event::fake([OrderShipmentDelivered::class]);

        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::Shipped,
        ]);

        $shipment = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::OutForDelivery,
            'shipped_at' => now()->subDays(2),
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment->id,
            'fulfillment_status' => FulfillmentStatus::Shipped,
        ]);

        (new MarkShipmentDeliveredAction)->execute($shipment);

        Event::assertDispatched(
            OrderShipmentDelivered::class,
            fn (OrderShipmentDelivered $e): bool => $e->order->id === $order->id
                && $e->shipment->id === $shipment->id
        );
    });

    it('does not dispatch delivery event when shipment cannot be delivered', function (): void {
        Event::fake([OrderShipmentDelivered::class]);

        $shipment = OrderShipping::query()->create([
            'order_id' => Order::factory()->create()->id,
            'status' => ShipmentStatus::Pending,
            'shipped_at' => now(),
        ]);

        (new MarkShipmentDeliveredAction)->execute($shipment);

        expect($shipment->refresh()->status)->toBe(ShipmentStatus::Pending);

        Event::assertNotDispatched(OrderShipmentDelivered::class);
    });
})
    ->group('workflows', 'order-fulfillment');
