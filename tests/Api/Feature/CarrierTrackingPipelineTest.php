<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Events\Orders\OrderCompleted;
use Shopper\Core\Events\Orders\OrderShipmentDelivered;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\OrderShipping;
use Tests\Core\Stubs\User;

uses(Tests\Api\TestCase::class);

/**
 * @return array<string, mixed>
 */
function upsTrackingPayload(): array
{
    return ['trackResponse' => ['shipment' => [['package' => [[
        'trackingNumber' => '1Z999AA10123456784',
        'currentStatus' => ['type' => 'D', 'code' => 'KB', 'description' => 'Delivered'],
        'deliveryDate' => [['type' => 'DEL', 'date' => '20260903']],
        'activity' => [
            [
                'location' => ['address' => ['city' => 'Paris', 'countryCode' => 'FR']],
                'status' => ['type' => 'D', 'code' => 'KB', 'description' => 'Delivered'],
                'date' => '20260903',
                'time' => '143000',
                'gmtDate' => '20260903',
                'gmtTime' => '12:30:00',
                'gmtOffset' => '+02:00',
            ],
            [
                'location' => ['address' => ['city' => 'Roissy', 'countryCode' => 'FR']],
                'status' => ['type' => 'I', 'code' => 'DP', 'description' => 'Departed from facility'],
                'gmtDate' => '20260902',
                'gmtTime' => '06:15:00',
            ],
        ],
    ]]]]]];
}

beforeEach(function (): void {
    config()->set('shopper.shipping.drivers.ups', [
        'enabled' => true,
        'sandbox' => false,
        'credentials' => [
            'client_id' => 'client',
            'client_secret' => 'secret',
            'user_id' => 'user',
            'account_number' => 'account',
        ],
    ]);

    Http::fake([
        'onlinetools.ups.com/security/v1/oauth/token' => Http::response(['access_token' => 'ups-token', 'expires_in' => 14399]),
        'onlinetools.ups.com/api/track/v1/details/*' => Http::response(upsTrackingPayload()),
    ]);

    $this->customer = User::factory()->create();
    $this->order = Order::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => OrderStatus::Processing,
        'payment_status' => PaymentStatus::Paid,
        'shipping_status' => ShippingStatus::Shipped,
    ]);
    $this->carrier = Carrier::factory()->create(['name' => 'UPS', 'driver' => 'ups']);
    $this->shipment = OrderShipping::factory()->create([
        'order_id' => $this->order->id,
        'carrier_id' => $this->carrier->id,
        'status' => ShipmentStatus::Pending,
        'tracking_number' => '1Z999AA10123456784',
    ]);
    $this->item = OrderItem::factory()->create([
        'order_id' => $this->order->id,
        'order_shipping_id' => $this->shipment->id,
        'fulfillment_status' => FulfillmentStatus::Shipped,
    ]);
});

it('walks a real UPS timeline from the scheduled command into the shipment', function (): void {
    $this->artisan('shopper:shipments:sync-tracking')
        ->expectsOutputToContain('Queued 1 shipment for tracking sync.')
        ->assertSuccessful();

    $this->shipment->refresh();

    expect($this->shipment->status)->toBe(ShipmentStatus::Delivered)
        ->and($this->shipment->received_at)->not->toBeNull()
        ->and($this->shipment->events()->orderBy('occurred_at')->pluck('status')->all())
        ->toBe([ShipmentStatus::InTransit, ShipmentStatus::OutForDelivery, ShipmentStatus::Delivered])
        ->and($this->shipment->events()->orderBy('occurred_at')->pluck('external_id')->all())
        ->toBe([
            'ups:20260902061500:DP',
            'ups:20260903123000:KB',
            'ups:delivered:1Z999AA10123456784',
        ]);
});

it('stays idempotent when the scheduled command runs again', function (): void {
    $this->artisan('shopper:shipments:sync-tracking')->assertSuccessful();

    $countAfterFirstRun = $this->shipment->events()->count();

    $this->artisan('shopper:shipments:sync-tracking')->assertSuccessful();

    expect($this->shipment->events()->count())->toBe($countAfterFirstRun);
});

it('serves the carrier timeline to the customer through the store API', function (): void {
    $this->artisan('shopper:shipments:sync-tracking')->assertSuccessful();

    Sanctum::actingAs($this->customer, ['store']);

    $included = collect(
        $this->getJson("/store/customers/me/orders/{$this->order->public_id}?include=shippings,shippings.events")
            ->assertOk()
            ->json('included')
    );

    $shipment = $included->firstWhere('type', 'order-shippings');
    $events = $included->where('type', 'order-shipping-events')
        ->sortBy('attributes.occurred_at')
        ->values();

    expect($shipment['attributes']['status'])->toBe('delivered')
        ->and($shipment['attributes']['carrier_name'])->toBe('UPS')
        ->and($shipment['attributes']['tracking_number'])->toBe('1Z999AA10123456784')
        ->and($shipment['attributes']['received_at'])->not->toBeNull()
        ->and($events->pluck('attributes.status')->all())->toBe(['in_transit', 'out_for_delivery', 'delivered'])
        ->and($events->first()['attributes']['location'])->toBe('Roissy, FR');
});

it('drops the shipment out of the polling rotation once the carrier delivers it', function (): void {
    $this->artisan('shopper:shipments:sync-tracking')->assertSuccessful();

    $this->artisan('shopper:shipments:sync-tracking')
        ->expectsOutputToContain('Queued 0 shipments for tracking sync.')
        ->assertSuccessful();
});

it('advances the order fulfillment steps when the carrier delivers the parcel', function (): void {
    Event::fake([OrderShipmentDelivered::class, OrderCompleted::class]);

    $this->artisan('shopper:shipments:sync-tracking')->assertSuccessful();

    expect($this->item->refresh()->fulfillment_status)->toBe(FulfillmentStatus::Delivered)
        ->and($this->order->refresh()->shipping_status)->toBe(ShippingStatus::Delivered)
        ->and($this->order->status)->toBe(OrderStatus::Completed);

    Event::assertDispatched(OrderShipmentDelivered::class);
    Event::assertDispatched(OrderCompleted::class);
});
