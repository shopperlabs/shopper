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

describe('Standard order — purchase to delivery', function (): void {
    it('starts with correct default statuses', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Pending,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'fulfillment_status' => FulfillmentStatus::Pending,
        ]);

        expect($order->isNew())->toBeTrue()
            ->and($order->isPaymentPending())->toBeTrue()
            ->and($order->isShippingPending())->toBeTrue()
            ->and($order->items)->each(
                fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Pending)
            );
    });

    it('transitions to processing after payment', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Pending,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        $order->update([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
        ]);

        $order->refresh();

        expect($order->isProcessing())->toBeTrue()
            ->and($order->isPaid())->toBeTrue()
            ->and($order->isShippingPending())->toBeTrue();
    });

    it('creates a shipment and assigns all items', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        $items = OrderItem::factory()->count(3)->create([
            'order_id' => $order->id,
            'fulfillment_status' => FulfillmentStatus::Pending,
        ]);

        $shipment = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::Pending,
            'shipped_at' => now(),
        ]);

        $order->items()->update(['order_shipping_id' => $shipment->id]);

        $shipment->refresh();

        expect($shipment->items)->toHaveCount(3)
            ->and($shipment->status)->toBe(ShipmentStatus::Pending);
    });

    it('marks items as shipped on carrier pickup and syncs order status', function (): void {
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

        $shipment->refresh();
        $order->refresh();

        expect($shipment->status)->toBe(ShipmentStatus::PickedUp)
            ->and($shipment->shipped_at)->not->toBeNull()
            ->and($shipment->items)->each(
                fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Shipped)
            )
            ->and($order->shipping_status)->toBe(ShippingStatus::Shipped);

        Event::assertDispatched(OrderShipped::class, fn (OrderShipped $e): bool => $e->order->id === $order->id);
    });

    it('delivers shipment, marks items delivered, and auto-completes order', function (): void {
        Event::fake([OrderShipmentDelivered::class]);

        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::Shipped,
        ]);

        $shipment = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::OutForDelivery,
            'shipped_at' => now()->subDay(),
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment->id,
            'fulfillment_status' => FulfillmentStatus::Shipped,
        ]);

        (new MarkShipmentDeliveredAction)->execute($shipment);

        $shipment->refresh();
        $order->refresh();

        expect($shipment->status)->toBe(ShipmentStatus::Delivered)
            ->and($shipment->received_at)->not->toBeNull()
            ->and($shipment->items)->each(
                fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Delivered)
            )
            ->and($order->shipping_status)->toBe(ShippingStatus::Delivered)
            ->and($order->status)->toBe(OrderStatus::Completed);

        Event::assertDispatched(OrderShipmentDelivered::class);
    });

    it('records shipment events in the timeline', function (): void {
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

        (new RecordShipmentEventAction)->execute($shipment, ShipmentStatus::PickedUp, [
            'location' => 'Paris Warehouse',
            'occurred_at' => now(),
        ]);

        (new RecordShipmentEventAction)->execute($shipment->refresh(), ShipmentStatus::InTransit, [
            'location' => 'Lyon Hub',
            'occurred_at' => now()->addHours(6),
        ]);

        $events = $shipment->events()->orderBy('occurred_at')->get();

        expect($events)->toHaveCount(2)
            ->and($events[0]->status)->toBe(ShipmentStatus::PickedUp)
            ->and($events[0]->location)->toBe('Paris Warehouse')
            ->and($events[1]->status)->toBe(ShipmentStatus::InTransit)
            ->and($events[1]->location)->toBe('Lyon Hub');
    });

    it('completes the full happy path end-to-end', function (): void {
        Event::fake([OrderShipped::class, OrderShipmentDelivered::class]);

        $order = Order::factory()->create([
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Pending,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'fulfillment_status' => FulfillmentStatus::Pending,
        ]);

        $order->update([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
        ]);

        $shipment = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::Pending,
            'shipped_at' => now(),
        ]);

        $order->items()->update(['order_shipping_id' => $shipment->id]);

        (new RecordShipmentEventAction)->execute($shipment, ShipmentStatus::PickedUp, [
            'occurred_at' => now(),
        ]);

        (new RecordShipmentEventAction)->execute($shipment->refresh(), ShipmentStatus::InTransit, [
            'occurred_at' => now()->addHours(3),
        ]);

        (new RecordShipmentEventAction)->execute($shipment->refresh(), ShipmentStatus::OutForDelivery, [
            'occurred_at' => now()->addHours(20),
        ]);

        (new MarkShipmentDeliveredAction)->execute($shipment->refresh());

        $order->refresh();
        $shipment->refresh();

        expect($order->status)->toBe(OrderStatus::Completed)
            ->and($order->payment_status)->toBe(PaymentStatus::Paid)
            ->and($order->shipping_status)->toBe(ShippingStatus::Delivered)
            ->and($shipment->status)->toBe(ShipmentStatus::Delivered)
            ->and($shipment->events)->toHaveCount(4)
            ->and($order->items)->each(
                fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Delivered)
            );

        Event::assertDispatched(OrderShipped::class, 1);
        Event::assertDispatched(OrderShipmentDelivered::class, 1);
    });
})
    ->group('workflows', 'order-fulfillment');
