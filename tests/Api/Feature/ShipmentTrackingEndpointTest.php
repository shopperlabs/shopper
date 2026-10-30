<?php

declare(strict_types=1);

use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderShipping;
use Shopper\Core\Models\OrderShippingEvent;
use Tests\Core\Stubs\User;

uses(Tests\Api\TestCase::class);

it('tracks a customer order shipment without authentication and lists its events in chronological order', function (): void {
    $shipment = OrderShipping::factory()->create([
        'order_id' => Order::factory()->create(['customer_id' => User::factory()->create()->id])->id,
        'carrier_id' => Carrier::factory()->create(['name' => 'Veronic Express'])->id,
        'status' => ShipmentStatus::InTransit,
        'tracking_number' => 'TRK-1',
    ]);

    OrderShippingEvent::factory()->for($shipment, 'shipment')->create([
        'status' => ShipmentStatus::InTransit,
        'occurred_at' => now()->subHour(),
    ]);
    OrderShippingEvent::factory()->for($shipment, 'shipment')->create([
        'status' => ShipmentStatus::PickedUp,
        'occurred_at' => now()->subHours(3),
    ]);

    $response = $this->getJson("/store/shipments/{$shipment->public_id}?include=events")
        ->assertOk()
        ->assertJsonPath('data.id', $shipment->public_id)
        ->assertJsonPath('data.attributes.status', 'in_transit')
        ->assertJsonPath('data.attributes.carrier_name', 'Veronic Express')
        ->assertJsonPath('data.attributes.tracking_number', 'TRK-1');

    expect(array_keys($response->json('data.relationships')))->toBe(['events'])
        ->and(collect($response->json('included'))->pluck('attributes.status')->all())->toBe(['picked_up', 'in_transit']);
});

it('refuses to expand anything but the events of a shipment', function (): void {
    $shipment = OrderShipping::factory()->create();

    $this->getJson("/store/shipments/{$shipment->public_id}?include=order")->assertStatus(400);
});

it('answers 404 for an unknown shipment', function (): void {
    OrderShipping::factory()->create();

    $this->getJson('/store/shipments/01JZZZZZZZZZZZZZZZZZZZZZZZ')->assertNotFound();
});
