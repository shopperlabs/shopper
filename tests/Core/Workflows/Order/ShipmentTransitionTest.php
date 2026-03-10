<?php

declare(strict_types=1);

use Shopper\Core\Actions\RecordShipmentEventAction;
use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\OrderShipping;

uses(Tests\Core\TestCase::class);

describe('Shipment state machine — invalid transitions', function (): void {
    it('rejects direct transition from pending to delivered', function (): void {
        $shipment = OrderShipping::query()->create([
            'order_id' => Order::factory()->create()->id,
            'status' => ShipmentStatus::Pending,
            'shipped_at' => now(),
        ]);

        expect($shipment->canTransitionTo(ShipmentStatus::Delivered))->toBeFalse();

        $result = (new RecordShipmentEventAction)->execute($shipment, ShipmentStatus::Delivered);

        expect($result)->toBeNull()
            ->and($shipment->refresh()->status)->toBe(ShipmentStatus::Pending);
    });

    it('rejects transitions from delivered (terminal state except return)', function (): void {
        $shipment = OrderShipping::query()->create([
            'order_id' => Order::factory()->create()->id,
            'status' => ShipmentStatus::Delivered,
            'shipped_at' => now()->subDays(3),
            'received_at' => now(),
        ]);

        expect($shipment->canTransitionTo(ShipmentStatus::InTransit))->toBeFalse()
            ->and($shipment->canTransitionTo(ShipmentStatus::OutForDelivery))->toBeFalse()
            ->and($shipment->canTransitionTo(ShipmentStatus::PickedUp))->toBeFalse()
            ->and($shipment->canTransitionTo(ShipmentStatus::Returned))->toBeTrue();
    });

    it('rejects all transitions from returned (fully terminal)', function (): void {
        $shipment = OrderShipping::query()->create([
            'order_id' => Order::factory()->create()->id,
            'status' => ShipmentStatus::Returned,
            'shipped_at' => now()->subDays(5),
            'returned_at' => now(),
        ]);

        expect($shipment->allowedTransitions())->toBeEmpty()
            ->and($shipment->canTransitionTo(ShipmentStatus::InTransit))->toBeFalse()
            ->and($shipment->canTransitionTo(ShipmentStatus::Delivered))->toBeFalse();
    });

    it('rejects skipping in-transit to go directly from picked up to out for delivery', function (): void {
        $shipment = OrderShipping::query()->create([
            'order_id' => Order::factory()->create()->id,
            'status' => ShipmentStatus::PickedUp,
            'shipped_at' => now(),
        ]);

        expect($shipment->canTransitionTo(ShipmentStatus::OutForDelivery))->toBeFalse()
            ->and($shipment->canTransitionTo(ShipmentStatus::Delivered))->toBeFalse()
            ->and($shipment->canTransitionTo(ShipmentStatus::InTransit))->toBeTrue();
    });

    it('allows retry after delivery failure', function (): void {
        $shipment = OrderShipping::query()->create([
            'order_id' => Order::factory()->create()->id,
            'status' => ShipmentStatus::DeliveryFailed,
            'shipped_at' => now()->subDays(2),
        ]);

        expect($shipment->canTransitionTo(ShipmentStatus::InTransit))->toBeTrue()
            ->and($shipment->canTransitionTo(ShipmentStatus::OutForDelivery))->toBeTrue()
            ->and($shipment->canTransitionTo(ShipmentStatus::Returned))->toBeTrue()
            ->and($shipment->canTransitionTo(ShipmentStatus::Delivered))->toBeFalse();
    });

    it('does not update item statuses on invalid transition', function (): void {
        $order = Order::factory()->create([
            'status' => OrderStatus::Processing,
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

        (new RecordShipmentEventAction)->execute($shipment, ShipmentStatus::Delivered);

        expect($shipment->refresh()->items)->each(
            fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Pending)
        );
    });
})
    ->group('workflows', 'order-fulfillment');
