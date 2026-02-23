<?php

declare(strict_types=1);

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

describe('Package return — full shipment returned', function (): void {
    it('marks items as cancelled and order as returned when shipment is returned', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::Shipped,
        ]);

        $shipment = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::InTransit,
            'shipped_at' => now()->subDays(2),
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment->id,
            'fulfillment_status' => FulfillmentStatus::Shipped,
        ]);

        (new RecordShipmentEventAction)->execute($shipment, ShipmentStatus::Returned, [
            'description' => 'Customer refused delivery',
            'occurred_at' => now(),
        ]);

        $shipment->refresh();
        $order->refresh();

        expect($shipment->status)->toBe(ShipmentStatus::Returned)
            ->and($shipment->returned_at)->not->toBeNull()
            ->and($shipment->items)->each(
                fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Cancelled)
            )
            ->and($order->shipping_status)->toBe(ShippingStatus::Returned);
    });

    it('records return event with description in timeline', function (): void {
        $shipment = OrderShipping::query()->create([
            'order_id' => Order::factory()->create([
                'status' => OrderStatus::Processing,
                'shipping_status' => ShippingStatus::Shipped,
            ])->id,
            'status' => ShipmentStatus::InTransit,
            'shipped_at' => now()->subDay(),
        ]);

        (new RecordShipmentEventAction)->execute($shipment, ShipmentStatus::Returned, [
            'description' => 'Incorrect address',
            'location' => 'Local Post Office',
            'occurred_at' => now(),
        ]);

        $event = $shipment->events()->first();

        expect($event->status)->toBe(ShipmentStatus::Returned)
            ->and($event->description)->toBe('Incorrect address')
            ->and($event->location)->toBe('Local Post Office');
    });

    it('handles return after delivery failure', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
            'shipping_status' => ShippingStatus::Shipped,
        ]);

        $shipment = OrderShipping::query()->create([
            'order_id' => $order->id,
            'status' => ShipmentStatus::OutForDelivery,
            'shipped_at' => now()->subDays(3),
        ]);

        OrderItem::factory()->count(2)->create([
            'order_id' => $order->id,
            'order_shipping_id' => $shipment->id,
            'fulfillment_status' => FulfillmentStatus::Shipped,
        ]);

        (new RecordShipmentEventAction)->execute($shipment, ShipmentStatus::DeliveryFailed, [
            'description' => 'Customer not home',
            'occurred_at' => now(),
        ]);

        expect($shipment->refresh()->status)->toBe(ShipmentStatus::DeliveryFailed);

        (new RecordShipmentEventAction)->execute($shipment->refresh(), ShipmentStatus::Returned, [
            'description' => 'Max delivery attempts reached',
            'occurred_at' => now()->addDay(),
        ]);

        $shipment->refresh();
        $order->refresh();

        expect($shipment->status)->toBe(ShipmentStatus::Returned)
            ->and($shipment->returned_at)->not->toBeNull()
            ->and($shipment->items)->each(
                fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Cancelled)
            )
            ->and($order->shipping_status)->toBe(ShippingStatus::Returned)
            ->and($shipment->events)->toHaveCount(2);
    });
})
    ->group('workflows', 'order-fulfillment');
