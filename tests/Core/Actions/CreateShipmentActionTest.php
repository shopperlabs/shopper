<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Shopper\Core\Actions\CreateShipmentAction;
use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Events\Orders\OrderShipmentCreated;
use Shopper\Core\Exceptions\CannotCreateEmptyShipmentException;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\OrderShipping;

uses(Tests\Core\TestCase::class);

it('creates a pending shipment, assigns the requested items and dispatches OrderShipmentCreated', function (): void {
    Event::fake([OrderShipmentCreated::class]);

    $order = Order::factory()->create();
    $carrier = Carrier::factory()->create();
    $items = OrderItem::factory()->count(3)->create([
        'order_id' => $order->id,
        'fulfillment_status' => FulfillmentStatus::Pending,
    ]);

    $shipment = (new CreateShipmentAction)->execute(
        order: $order,
        carrierId: $carrier->id,
        itemIds: [$items[0]->id, $items[1]->id],
        trackingNumber: 'TRK-1',
        trackingUrl: 'https://carrier.test/TRK-1',
        description: 'Label created',
    );

    expect($shipment->status)->toBe(ShipmentStatus::Pending)
        ->and($shipment->carrier_id)->toBe($carrier->id)
        ->and($shipment->tracking_number)->toBe('TRK-1')
        ->and($shipment->shipped_at)->toBeNull()
        ->and($shipment->items()->pluck('id')->all())->toEqualCanonicalizing([$items[0]->id, $items[1]->id])
        ->and($items[2]->refresh()->order_shipping_id)->toBeNull()
        ->and($shipment->events()->count())->toBe(1)
        ->and($shipment->events()->first()->description)->toBe('Label created');

    Event::assertDispatched(OrderShipmentCreated::class, fn (OrderShipmentCreated $e): bool => $e->shipment->is($shipment));
});

it('refuses to create a shipment when every requested item is already attached elsewhere', function (): void {
    $order = Order::factory()->create();
    $existing = OrderShipping::factory()->create(['order_id' => $order->id]);
    $item = OrderItem::factory()->create([
        'order_id' => $order->id,
        'order_shipping_id' => $existing->id,
    ]);

    expect(fn () => (new CreateShipmentAction)->execute($order, null, [$item->id]))
        ->toThrow(CannotCreateEmptyShipmentException::class)
        ->and($item->refresh()->order_shipping_id)->toBe($existing->id)
        ->and(OrderShipping::query()->count())->toBe(1);
});
