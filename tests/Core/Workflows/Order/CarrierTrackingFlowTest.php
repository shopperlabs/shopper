<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Event;
use Shopper\Core\Actions\RecordShipmentEventAction;
use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Core\Events\Orders\OrderShipmentDelivered;
use Shopper\Core\Events\Orders\OrderShipmentDeliveryFailed;
use Shopper\Core\Events\Orders\OrderShipmentEventRecorded;
use Shopper\Core\Events\Orders\OrderShipmentReturned;
use Shopper\Core\Events\Orders\OrderShipped;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\OrderShipping;

uses(Tests\Core\TestCase::class);

function carrierShipment(ShipmentStatus $status = ShipmentStatus::Pending, PaymentStatus $paymentStatus = PaymentStatus::Paid): OrderShipping
{
    $order = Order::factory()->create([
        'status' => OrderStatus::Processing,
        'payment_status' => $paymentStatus,
        'shipping_status' => ShippingStatus::Unfulfilled,
    ]);

    $shipment = OrderShipping::factory()->create([
        'order_id' => $order->id,
        'status' => $status,
        'shipped_at' => null,
    ]);

    OrderItem::factory()->count(2)->create([
        'order_id' => $order->id,
        'order_shipping_id' => $shipment->id,
        'fulfillment_status' => FulfillmentStatus::Pending,
    ]);

    return $shipment;
}

function carrierEvent(OrderShipping $shipment, ShipmentStatus $status, int $hoursAgo, string $externalId, ?string $location = null): mixed
{
    return (new RecordShipmentEventAction)->execute($shipment, $status, [
        'location' => $location,
        'occurred_at' => now()->subHours($hoursAgo),
        'external_id' => $externalId,
    ], fromCarrier: true);
}

describe('Carrier tracking feed', function (): void {
    it('handles legacy items without a fulfillment status', function (): void {
        $shipment = carrierShipment();
        $order = $shipment->order;

        $inShipment = OrderItem::factory()->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment->id,
            'fulfillment_status' => null,
        ]);

        $outside = OrderItem::factory()->create([
            'order_id' => $order->id,
            'order_shipping_id' => null,
            'fulfillment_status' => null,
        ]);

        carrierEvent($shipment, ShipmentStatus::Delivered, 1, 'evt-legacy');

        expect($inShipment->refresh()->fulfillment_status)->toBe(FulfillmentStatus::Delivered)
            ->and($outside->refresh()->fulfillment_status)->toBeNull()
            ->and($order->refresh()->status)->toBe(OrderStatus::Processing);
    });

    it('completes the order when payment lands after delivery', function (): void {
        $shipment = carrierShipment(paymentStatus: PaymentStatus::Pending);
        $order = $shipment->order;

        carrierEvent($shipment, ShipmentStatus::Delivered, 1, 'evt-1');

        expect($order->refresh()->status)->toBe(OrderStatus::Processing);

        $order->update(['payment_status' => PaymentStatus::Paid]);

        event(new OrderPaid($order));

        expect($order->refresh()->status)->toBe(OrderStatus::Completed);
    });

    it('keeps a paid order open while its items are still in preparation', function (OrderStatus $status): void {
        $order = Order::factory()->create([
            'status' => $status,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'fulfillment_status' => FulfillmentStatus::Pending,
        ]);

        event(new OrderPaid($order));

        expect($order->refresh()->status)->toBe($status);
    })->with([
        'new' => OrderStatus::New,
        'processing' => OrderStatus::Processing,
    ]);

    it('walks the carrier timeline and fires each side effect once', function (): void {
        Event::fake([OrderShipped::class, OrderShipmentDelivered::class]);

        $shipment = carrierShipment();

        carrierEvent($shipment, ShipmentStatus::PickedUp, 30, 'evt-1', 'Douala');
        carrierEvent($shipment, ShipmentStatus::InTransit, 20, 'evt-2', 'Yaoundé hub');
        carrierEvent($shipment, ShipmentStatus::InTransit, 10, 'evt-3', 'Bafoussam hub');
        carrierEvent($shipment, ShipmentStatus::Delivered, 1, 'evt-4', 'Bafoussam');

        $shipment->refresh();
        $order = $shipment->order->refresh();

        expect($shipment->status)->toBe(ShipmentStatus::Delivered)
            ->and($shipment->events)->toHaveCount(4)
            ->and($shipment->shipped_at?->toDateTimeString())->toBe(now()->subHours(30)->toDateTimeString())
            ->and($shipment->received_at?->toDateTimeString())->toBe(now()->subHour()->toDateTimeString())
            ->and($shipment->items)->each(fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Delivered))
            ->and($order->status)->toBe(OrderStatus::Completed)
            ->and($order->shipping_status)->toBe(ShippingStatus::Delivered);

        Event::assertDispatched(OrderShipped::class, 1);
        Event::assertDispatched(OrderShipmentDelivered::class, 1);
    });

    it('keeps a stale lower ranked scan in the timeline without moving the status', function (): void {
        Event::fake([OrderShipmentEventRecorded::class]);

        $shipment = carrierShipment();

        carrierEvent($shipment, ShipmentStatus::Delivered, 1, 'evt-delivered');
        $receivedAt = $shipment->refresh()->received_at;

        $event = carrierEvent($shipment, ShipmentStatus::InTransit, 12, 'evt-late-scan', 'Late hub');

        $shipment->refresh();

        expect($event)->not->toBeNull()
            ->and($shipment->status)->toBe(ShipmentStatus::Delivered)
            ->and($shipment->received_at)->toEqual($receivedAt)
            ->and($shipment->events)->toHaveCount(2)
            ->and($shipment->events->firstWhere('external_id', 'evt-late-scan')->location)->toBe('Late hub');

        Event::assertDispatched(
            OrderShipmentEventRecorded::class,
            fn (OrderShipmentEventRecorded $e): bool => $e->event->external_id === 'evt-late-scan' && ! $e->statusChanged
        );
    });

    it('ignores a redelivered carrier event', function (): void {
        $shipment = carrierShipment();

        $first = carrierEvent($shipment, ShipmentStatus::PickedUp, 2, 'evt-dup');
        $second = carrierEvent($shipment, ShipmentStatus::PickedUp, 2, 'evt-dup');

        expect($first)->not->toBeNull()
            ->and($second)->toBeNull()
            ->and($shipment->events()->count())->toBe(1);
    });

    it('delivers a pending shipment from a single carrier event', function (): void {
        Event::fake([OrderShipped::class, OrderShipmentDelivered::class]);

        $shipment = carrierShipment();

        carrierEvent($shipment, ShipmentStatus::Delivered, 1, 'evt-only');

        $shipment->refresh();
        $order = $shipment->order->refresh();

        expect($shipment->status)->toBe(ShipmentStatus::Delivered)
            ->and($shipment->shipped_at)->not->toBeNull()
            ->and($shipment->received_at)->not->toBeNull()
            ->and($shipment->items)->each(fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Delivered))
            ->and($order->status)->toBe(OrderStatus::Completed);

        Event::assertDispatched(OrderShipped::class, 1);
        Event::assertDispatched(OrderShipmentDelivered::class, 1);
    });

    it('logs a failed attempt reported after delivery without reverting the status', function (): void {
        Event::fake([OrderShipmentDeliveryFailed::class]);

        $shipment = carrierShipment();

        carrierEvent($shipment, ShipmentStatus::Delivered, 2, 'evt-delivered');
        carrierEvent($shipment, ShipmentStatus::DeliveryFailed, 1, 'evt-failed');

        expect($shipment->refresh()->status)->toBe(ShipmentStatus::Delivered)
            ->and($shipment->events)->toHaveCount(2);

        Event::assertNotDispatched(OrderShipmentDeliveryFailed::class);
    });

    it('swaps between same ranked statuses by occurrence date', function (): void {
        Event::fake([OrderShipmentDeliveryFailed::class]);

        $shipment = carrierShipment(ShipmentStatus::InTransit);

        carrierEvent($shipment, ShipmentStatus::OutForDelivery, 6, 'evt-ofd-1');
        carrierEvent($shipment, ShipmentStatus::DeliveryFailed, 4, 'evt-failed');
        carrierEvent($shipment, ShipmentStatus::OutForDelivery, 2, 'evt-ofd-2');
        carrierEvent($shipment, ShipmentStatus::DeliveryFailed, 5, 'evt-failed-late');

        expect($shipment->refresh()->status)->toBe(ShipmentStatus::OutForDelivery)
            ->and($shipment->events)->toHaveCount(4);

        Event::assertDispatched(OrderShipmentDeliveryFailed::class, 1);
    });

    it('does not complete an unpaid order on delivery', function (): void {
        $shipment = carrierShipment(paymentStatus: PaymentStatus::Pending);

        carrierEvent($shipment, ShipmentStatus::Delivered, 1, 'evt-cod');

        $shipment->refresh();

        expect($shipment->status)->toBe(ShipmentStatus::Delivered)
            ->and($shipment->order->refresh()->status)->toBe(OrderStatus::Processing);
    });

    it('returns a delivered shipment', function (): void {
        Event::fake([OrderShipmentReturned::class]);

        $shipment = carrierShipment();

        carrierEvent($shipment, ShipmentStatus::Delivered, 5, 'evt-delivered');
        carrierEvent($shipment, ShipmentStatus::Returned, 1, 'evt-returned');

        $shipment->refresh();

        expect($shipment->status)->toBe(ShipmentStatus::Returned)
            ->and($shipment->returned_at?->toDateTimeString())->toBe(now()->subHour()->toDateTimeString())
            ->and($shipment->items)->each(fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Cancelled))
            ->and($shipment->order->refresh()->shipping_status)->toBe(ShippingStatus::Returned);

        Event::assertDispatched(OrderShipmentReturned::class, 1);
    });

    it('treats the first scan past pickup as the pickup', function (): void {
        Event::fake([OrderShipped::class]);

        $shipment = carrierShipment();

        carrierEvent($shipment, ShipmentStatus::InTransit, 3, 'evt-scan', 'Hub');

        $shipment->refresh();

        expect($shipment->status)->toBe(ShipmentStatus::InTransit)
            ->and($shipment->shipped_at?->toDateTimeString())->toBe(now()->subHours(3)->toDateTimeString())
            ->and($shipment->items)->each(fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Shipped))
            ->and($shipment->order->refresh()->shipping_status)->toBe(ShippingStatus::Shipped);

        Event::assertDispatched(OrderShipped::class, 1);
    });

    it('reads the order from the database rather than from a stale loaded relation', function (): void {
        $shipment = carrierShipment();
        $loaded = OrderShipping::query()->with('order')->findOrFail($shipment->id);

        Order::query()->findOrFail($shipment->order_id)->update(['status' => OrderStatus::Cancelled]);

        carrierEvent($loaded, ShipmentStatus::Delivered, 1, 'evt-late');

        expect($loaded->refresh()->status)->toBe(ShipmentStatus::Delivered)
            ->and(Order::query()->findOrFail($shipment->order_id)->status)->toBe(OrderStatus::Cancelled);
    });

    it('rejects a duplicate external id at the database level', function (): void {
        $shipment = carrierShipment();

        $shipment->logEvent(ShipmentStatus::PickedUp, ['external_id' => 'evt-1']);

        expect(fn () => $shipment->logEvent(ShipmentStatus::PickedUp, ['external_id' => 'evt-1']))
            ->toThrow(QueryException::class);
    });
})
    ->group('workflows', 'order-fulfillment');
