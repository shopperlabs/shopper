<?php

declare(strict_types=1);

use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\OrderShipping;
use Shopper\Shipping\Facades\Shipping;
use Tests\Api\Stubs\FakeShippingDriver;

uses(Tests\Api\TestCase::class);

beforeEach(function (): void {
    Shipping::extend('fake', fn (): FakeShippingDriver => new FakeShippingDriver(webhooks: true));

    $carrier = Carrier::factory()->create(['driver' => 'fake']);

    $this->order = Order::factory()->create([
        'status' => OrderStatus::Processing,
        'payment_status' => PaymentStatus::Paid,
        'shipping_status' => ShippingStatus::Unfulfilled,
    ]);

    $this->shipment = OrderShipping::factory()->create([
        'order_id' => $this->order->id,
        'carrier_id' => $carrier->id,
        'status' => ShipmentStatus::Pending,
        'shipped_at' => null,
        'tracking_number' => 'TRK-1',
    ]);

    OrderItem::factory()->create([
        'order_id' => $this->order->id,
        'order_shipping_id' => $this->shipment->id,
        'fulfillment_status' => FulfillmentStatus::Pending,
    ]);

    $this->payload = [
        'tracking_number' => 'TRK-1',
        'status' => 'delivered',
        'events' => [
            ['id' => 'e1', 'status' => 'picked_up', 'occurred_at' => '2026-08-01T08:00:00Z', 'location' => 'Douala'],
            ['id' => 'e2', 'status' => 'in_transit', 'occurred_at' => '2026-08-01T14:00:00Z', 'location' => 'Yaoundé hub'],
            ['id' => 'e3', 'status' => 'delivered', 'occurred_at' => '2026-08-02T10:00:00Z', 'location' => 'Yaoundé'],
        ],
    ];
});

it('returns 404 for an unknown driver', function (): void {
    $this->postJson('/store/webhooks/shipping/unknown', $this->payload)->assertNotFound();
});

it('returns 404 for a driver without webhook support', function (): void {
    Shipping::extend('nohook', fn (): FakeShippingDriver => new FakeShippingDriver);

    $this->postJson('/store/webhooks/shipping/nohook', $this->payload)->assertNotFound();
});

it('rejects an unverified payload with a 400', function (): void {
    $this->postJson('/store/webhooks/shipping/fake', [...$this->payload, 'signature' => 'invalid'])
        ->assertStatus(400);

    expect($this->shipment->refresh()->status)->toBe(ShipmentStatus::Pending)
        ->and($this->shipment->events()->count())->toBe(0);
});

it('acknowledges a notification the driver ignores', function (): void {
    $this->postJson('/store/webhooks/shipping/fake', ['type' => 'ping'])
        ->assertOk()
        ->assertJson(['received' => true]);

    expect($this->shipment->events()->count())->toBe(0);
});

it('applies the carrier timeline to the shipment and its order', function (): void {
    $this->postJson('/store/webhooks/shipping/fake', $this->payload)
        ->assertOk()
        ->assertJson(['received' => true]);

    $this->shipment->refresh();
    $this->order->refresh();

    expect($this->shipment->status)->toBe(ShipmentStatus::Delivered)
        ->and($this->shipment->events()->orderBy('occurred_at')->pluck('location')->all())->toBe(['Douala', 'Yaoundé hub', 'Yaoundé'])
        ->and($this->shipment->shipped_at?->toIso8601String())->toBe('2026-08-01T08:00:00+00:00')
        ->and($this->shipment->received_at?->toIso8601String())->toBe('2026-08-02T10:00:00+00:00')
        ->and($this->shipment->items)->each(fn ($item) => $item->fulfillment_status->toBe(FulfillmentStatus::Delivered))
        ->and($this->order->status)->toBe(OrderStatus::Completed);
});

it('is idempotent: a redelivered webhook adds nothing', function (): void {
    $this->postJson('/store/webhooks/shipping/fake', $this->payload)->assertOk();
    $this->postJson('/store/webhooks/shipping/fake', $this->payload)->assertOk();

    expect($this->shipment->events()->count())->toBe(3);
});

it('acknowledges an unknown tracking number without recording anything', function (): void {
    $this->postJson('/store/webhooks/shipping/fake', [...$this->payload, 'tracking_number' => 'TRK-UNKNOWN'])
        ->assertOk();

    expect($this->shipment->refresh()->status)->toBe(ShipmentStatus::Pending)
        ->and($this->shipment->events()->count())->toBe(0);
});

it('returns 404 for a webhook driver that is not configured', function (): void {
    Shipping::extend('unconfigured', static fn (): FakeShippingDriver => new FakeShippingDriver(configured: false, webhooks: true));

    $this->postJson('/store/webhooks/shipping/unconfigured', $this->payload)->assertNotFound();
});

it('routes a carrier update to the sole open shipment when a closed one shares its tracking number', function (): void {
    OrderShipping::factory()->create([
        'carrier_id' => $this->shipment->carrier_id,
        'status' => ShipmentStatus::Delivered,
        'tracking_number' => 'TRK-1',
    ]);

    $this->postJson('/store/webhooks/shipping/fake', $this->payload)->assertOk();

    expect($this->shipment->refresh()->status)->toBe(ShipmentStatus::Delivered)
        ->and($this->shipment->events()->count())->toBe(3);
});

it('refuses to guess when every shipment sharing a tracking number is closed', function (): void {
    $this->shipment->update(['status' => ShipmentStatus::Delivered]);

    $other = OrderShipping::factory()->create([
        'carrier_id' => $this->shipment->carrier_id,
        'status' => ShipmentStatus::Returned,
        'tracking_number' => 'TRK-1',
    ]);

    $this->postJson('/store/webhooks/shipping/fake', $this->payload)->assertOk();

    expect($this->shipment->events()->count() + $other->events()->count())->toBe(0);
});

it('refuses to guess between two open shipments sharing a tracking number', function (): void {
    $other = OrderShipping::factory()->create([
        'carrier_id' => $this->shipment->carrier_id,
        'status' => ShipmentStatus::Pending,
        'shipped_at' => null,
        'tracking_number' => 'TRK-1',
    ]);

    $this->postJson('/store/webhooks/shipping/fake', $this->payload)->assertOk();

    expect($this->shipment->refresh()->status)->toBe(ShipmentStatus::Pending)
        ->and($other->refresh()->status)->toBe(ShipmentStatus::Pending)
        ->and($this->shipment->events()->count() + $other->events()->count())->toBe(0);
});
