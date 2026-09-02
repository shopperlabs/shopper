<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Queue;
use Shopper\Core\Enum\ShipmentEventSource;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\OrderShipping;
use Shopper\Shipping\Actions\ApplyTrackingInfoAction;
use Shopper\Shipping\DataTransferObjects\TrackingEvent;
use Shopper\Shipping\DataTransferObjects\TrackingInfo;
use Shopper\Shipping\Facades\Shipping;
use Shopper\Shipping\Jobs\SyncShipmentTrackingJob;
use Tests\Api\Stubs\FakeShippingDriver;

uses(Tests\Api\TestCase::class);

beforeEach(function (): void {
    $driver = new FakeShippingDriver(tracking: new TrackingInfo(
        trackingNumber: 'TRK-1',
        status: ShipmentStatus::InTransit,
        events: [
            new TrackingEvent(ShipmentStatus::InTransit, 'Departed hub', new DateTimeImmutable('2026-08-01 14:00:00'), 'Yaoundé hub'),
            new TrackingEvent(ShipmentStatus::PickedUp, 'Collected', new DateTimeImmutable('2026-08-01 08:00:00'), 'Douala'),
        ],
    ));

    Shipping::extend('fake', static fn (): FakeShippingDriver => $driver);

    $this->driver = $driver;

    $this->carrier = Carrier::factory()->create(['driver' => 'fake']);
});

it('queues one job per undelivered shipment whose carrier supports tracking', function (): void {
    Queue::fake();

    $manual = Carrier::factory()->create(['driver' => 'manual']);

    OrderShipping::factory()->create(['carrier_id' => $this->carrier->id, 'status' => ShipmentStatus::PickedUp, 'tracking_number' => 'TRK-1']);
    OrderShipping::factory()->create(['carrier_id' => $this->carrier->id, 'status' => ShipmentStatus::Delivered, 'tracking_number' => 'TRK-2']);
    OrderShipping::factory()->create(['carrier_id' => $this->carrier->id, 'status' => ShipmentStatus::Pending, 'tracking_number' => null]);
    OrderShipping::factory()->create(['carrier_id' => $manual->id, 'status' => ShipmentStatus::Pending, 'tracking_number' => 'TRK-3']);

    $this->artisan('shopper:shipments:sync-tracking')
        ->expectsOutputToContain('Queued 1 shipment for tracking sync.')
        ->assertSuccessful();

    Queue::assertPushed(SyncShipmentTrackingJob::class, 1);
});

it('does not queue a shipment again while its previous job is still waiting', function (): void {
    Queue::fake();

    OrderShipping::factory()->create(['carrier_id' => $this->carrier->id, 'status' => ShipmentStatus::PickedUp, 'tracking_number' => 'TRK-1']);

    $this->artisan('shopper:shipments:sync-tracking')->assertSuccessful();
    $this->artisan('shopper:shipments:sync-tracking')->assertSuccessful();

    Queue::assertPushed(SyncShipmentTrackingJob::class, 1);
});

it('pulls the carrier timeline into the shipment and stays idempotent across runs', function (): void {
    $shipment = OrderShipping::factory()->create([
        'carrier_id' => $this->carrier->id,
        'status' => ShipmentStatus::Pending,
        'shipped_at' => null,
        'tracking_number' => 'TRK-1',
    ]);

    $action = resolve(ApplyTrackingInfoAction::class);

    (new SyncShipmentTrackingJob($shipment->id))->handle($action);
    (new SyncShipmentTrackingJob($shipment->id))->handle($action);

    $shipment->refresh();

    expect($this->driver->trackings)->toBe(2)
        ->and($shipment->status)->toBe(ShipmentStatus::InTransit)
        ->and($shipment->shipped_at?->toDateTimeString())->toBe('2026-08-01 08:00:00')
        ->and($shipment->events()->orderBy('occurred_at')->pluck('status')->all())->toBe([ShipmentStatus::PickedUp, ShipmentStatus::InTransit]);
});

it('skips a delivered shipment without calling the carrier', function (): void {
    $shipment = OrderShipping::factory()->create([
        'carrier_id' => $this->carrier->id,
        'status' => ShipmentStatus::Delivered,
        'tracking_number' => 'TRK-1',
    ]);

    (new SyncShipmentTrackingJob($shipment->id))->handle(resolve(ApplyTrackingInfoAction::class));

    expect($this->driver->trackings)->toBe(0)
        ->and($shipment->events()->count())->toBe(0);
});

it('deduplicates carrier events without an external id by fingerprint', function (): void {
    Shipping::extend('fake', static fn (): FakeShippingDriver => new FakeShippingDriver(tracking: new TrackingInfo(
        trackingNumber: 'TRK-1',
        status: ShipmentStatus::InTransit,
        events: [
            new TrackingEvent(ShipmentStatus::InTransit, 'Departed hub', new DateTimeImmutable('2026-08-01 14:00:00'), 'Yaoundé hub'),
        ],
    )));

    $shipment = OrderShipping::factory()->create([
        'carrier_id' => $this->carrier->id,
        'status' => ShipmentStatus::Pending,
        'tracking_number' => 'TRK-1',
    ]);

    $action = resolve(ApplyTrackingInfoAction::class);

    (new SyncShipmentTrackingJob($shipment->id))->handle($action);
    (new SyncShipmentTrackingJob($shipment->id))->handle($action);

    expect($shipment->events()->count())->toBe(1)
        ->and($shipment->events()->first()->source)->toBe(ShipmentEventSource::Carrier);
});

it('records a status-only tracking response once across runs', function (): void {
    Shipping::extend('fake', fn (): FakeShippingDriver => new FakeShippingDriver(tracking: new TrackingInfo(
        trackingNumber: 'TRK-1',
        status: ShipmentStatus::InTransit,
    )));

    $shipment = OrderShipping::factory()->create([
        'carrier_id' => $this->carrier->id,
        'status' => ShipmentStatus::Pending,
        'shipped_at' => null,
        'tracking_number' => 'TRK-1',
    ]);

    $action = resolve(ApplyTrackingInfoAction::class);

    (new SyncShipmentTrackingJob($shipment->id))->handle($action);
    (new SyncShipmentTrackingJob($shipment->id))->handle($action);

    expect($shipment->events()->count())->toBe(1)
        ->and($shipment->refresh()->status)->toBe(ShipmentStatus::InTransit)
        ->and($shipment->shipped_at)->not->toBeNull();
});
