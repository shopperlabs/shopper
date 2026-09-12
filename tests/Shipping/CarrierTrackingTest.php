<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\FedEx\FedExDriver;
use Shopper\Shipping\DataTransferObjects\TrackingEvent;
use Shopper\Shipping\Exceptions\ShippingException;
use Shopper\Shipping\Exceptions\TrackingNotFoundException;
use Shopper\Ups\UpsDriver;

uses(Tests\Shipping\TestCase::class);

const UPS_NUMBER = '1Z999AA10123456784';

const FEDEX_NUMBER = '794644746162';

function upsDriver(bool $sandbox = false): UpsDriver
{
    return new UpsDriver('client', 'secret', 'user', 'account', sandbox: $sandbox);
}

function fedexDriver(bool $sandbox = false): FedExDriver
{
    return new FedExDriver('client', 'secret', 'account', sandbox: $sandbox);
}

/**
 * @param  array<string, mixed>  $package
 * @return array<string, mixed>
 */
function upsResponse(array $package): array
{
    return ['trackResponse' => ['shipment' => [['package' => [$package]]]]];
}

/**
 * @return array<string, mixed>
 */
function upsPackage(): array
{
    return upsResponse([
        'trackingNumber' => UPS_NUMBER,
        'currentStatus' => ['type' => 'D', 'code' => 'KB', 'description' => 'Delivered'],
        'deliveryDate' => [
            ['type' => 'SDD', 'date' => '20260903'],
            ['type' => 'DEL', 'date' => '20260903'],
        ],
        'activity' => [
            [
                'location' => ['address' => ['city' => 'Paris', 'stateProvince' => '', 'countryCode' => 'FR']],
                'status' => ['type' => 'D', 'code' => 'KB', 'description' => 'Delivered', 'statusCode' => '011'],
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
            [
                'status' => ['type' => 'P', 'code' => 'OR', 'description' => 'Origin scan'],
                'date' => '20260901',
                'time' => '170000',
                'gmtOffset' => '+02:00',
            ],
            [
                'status' => ['type' => 'M', 'code' => 'MP', 'description' => 'Shipper created a label'],
                'date' => '20260901',
                'time' => '090000',
            ],
        ],
    ]);
}

/**
 * @param  array<string, mixed>  $result
 * @return array<string, mixed>
 */
function fedexResponse(array $result): array
{
    return ['output' => ['completeTrackResults' => [['trackResults' => [$result]]]]];
}

/**
 * @return array<string, mixed>
 */
function fedexPackage(): array
{
    return fedexResponse([
        'trackingNumberInfo' => ['trackingNumber' => FEDEX_NUMBER],
        'latestStatusDetail' => ['code' => 'DL', 'statusByLocale' => 'Delivered', 'description' => 'Delivered'],
        'dateAndTimes' => [
            ['type' => 'ACTUAL_DELIVERY', 'dateTime' => '2026-09-03T14:31:00-05:00'],
            ['type' => 'ESTIMATED_DELIVERY', 'dateTime' => '2026-09-03T20:00:00-05:00'],
        ],
        'scanEvents' => [
            [
                'date' => '2026-09-03T14:31:00-05:00',
                'eventType' => 'DL',
                'eventDescription' => 'Delivered',
                'derivedStatusCode' => 'DL',
                'scanLocation' => ['city' => 'MEMPHIS', 'stateOrProvinceCode' => 'TN', 'countryCode' => 'US'],
            ],
            [
                'date' => '2026-09-02T07:04:00-05:00',
                'eventType' => 'DP',
                'eventDescription' => 'Departed FedEx location',
                'derivedStatusCode' => 'IT',
                'scanLocation' => ['city' => 'MEMPHIS', 'countryCode' => 'US'],
            ],
            ['date' => 'not-a-date', 'eventType' => 'OC', 'derivedStatusCode' => 'OC'],
        ],
    ]);
}

function fakeUps(mixed $tracking, int $status = 200, mixed $token = null): void
{
    Http::fake([
        'onlinetools.ups.com/security/v1/oauth/token' => Http::response($token ?? ['access_token' => 'ups-token', 'expires_in' => 14399]),
        'onlinetools.ups.com/api/track/v1/details/*' => Http::response($tracking, $status),
    ]);
}

function fakeFedEx(mixed $tracking, int $status = 200, mixed $token = null): void
{
    Http::fake([
        'apis.fedex.com/oauth/token' => Http::response($token ?? ['access_token' => 'fedex-token', 'expires_in' => 3599]),
        'apis.fedex.com/track/v1/trackingnumbers' => Http::response($tracking, $status),
    ]);
}

/**
 * @return array<int, string>
 */
function eventIds(mixed $info): array
{
    return collect($info->events)->map(fn (TrackingEvent $event): string => (string) $event->externalId)->all();
}

/**
 * @return array<int, string>
 */
function eventStatuses(mixed $info): array
{
    return collect($info->events)->map(fn (TrackingEvent $event): string => $event->status->value)->all();
}

it('turns the UPS activity feed into shipment events timed from the GMT fields', function (): void {
    fakeUps(upsPackage());

    $info = upsDriver()->track(UPS_NUMBER);

    expect($info->status)->toBe(ShipmentStatus::Delivered)
        ->and($info->statusDescription)->toBe('Delivered')
        ->and($info->deliveredAt?->format('Y-m-d H:i:s e'))->toBe('2026-09-03 12:30:00 UTC')
        ->and($info->estimatedDelivery?->format('Y-m-d H:i:s'))->toBe('2026-09-03 00:00:00')
        ->and(eventStatuses($info))->toBe(['out_for_delivery', 'in_transit', 'picked_up', 'delivered'])
        ->and(eventIds($info))->toBe([
            'ups:20260903123000:KB',
            'ups:20260902061500:DP',
            'ups:20260901150000:OR',
            'ups:delivered:'.UPS_NUMBER,
        ])
        ->and(collect($info->events)->first()->location)->toBe('Paris, FR');
});

it('times a UPS activity from its local stamp and offset when the GMT fields are absent', function (): void {
    fakeUps(upsPackage());

    $picked = collect(upsDriver()->track(UPS_NUMBER)->events)
        ->firstWhere(fn (TrackingEvent $event): bool => $event->status === ShipmentStatus::PickedUp);

    expect($picked->occurredAt->format('Y-m-d H:i:s'))->toBe('2026-09-01 15:00:00');
});

it('never guesses the instant of a UPS activity that carries neither a GMT stamp nor an offset', function (): void {
    fakeUps(upsPackage());

    expect(collect(upsDriver()->track(UPS_NUMBER)->events)->pluck('description')->all())
        ->not->toContain('Shipper created a label');
});

it('keeps a UPS parcel out of the delivered state until the carrier dates the delivery', function (): void {
    $payload = upsPackage();
    unset($payload['trackResponse']['shipment'][0]['package'][0]['deliveryDate'][1]);

    fakeUps($payload);

    $info = upsDriver()->track(UPS_NUMBER);

    expect($info->status)->toBe(ShipmentStatus::OutForDelivery)
        ->and($info->deliveredAt)->toBeNull()
        ->and($info->events)->toHaveCount(3);
});

it('refuses to invent a UPS delivery instant when no scan carries the delivery date', function (): void {
    $payload = upsPackage();
    $payload['trackResponse']['shipment'][0]['package'][0]['deliveryDate'] = [['type' => 'DEL', 'date' => '20260905']];

    fakeUps($payload);

    $info = upsDriver()->track(UPS_NUMBER);

    expect($info->deliveredAt)->toBeNull()
        ->and($info->status)->toBe(ShipmentStatus::OutForDelivery);
});

it('prefers the rescheduled UPS delivery date over the scheduled one', function (): void {
    $payload = upsPackage();
    $payload['trackResponse']['shipment'][0]['package'][0]['deliveryDate'] = [
        ['type' => 'SDD', 'date' => '20260903'],
        ['type' => 'RDD', 'date' => '20260904'],
    ];

    fakeUps($payload);

    expect(upsDriver()->track(UPS_NUMBER)->estimatedDelivery?->format('Y-m-d'))->toBe('2026-09-04');
});

it('rejects a UPS delivery date that is not a real calendar day', function (): void {
    $payload = upsPackage();
    $payload['trackResponse']['shipment'][0]['package'][0]['deliveryDate'] = [
        ['type' => 'SDD', 'date' => '20261345'],
        ['type' => 'DEL', 'date' => '00000000'],
    ];

    fakeUps($payload);

    $info = upsDriver()->track(UPS_NUMBER);

    expect($info->estimatedDelivery)->toBeNull()
        ->and($info->deliveredAt)->toBeNull();
});

it('reads the UPS status from the current status when no scan can be timed', function (): void {
    fakeUps(upsResponse([
        'trackingNumber' => UPS_NUMBER,
        'currentStatus' => ['type' => 'M', 'description' => 'Label created'],
        'activity' => [],
    ]));

    $info = upsDriver()->track(UPS_NUMBER);

    expect($info->events)->toBe([])
        ->and($info->status)->toBe(ShipmentStatus::Pending)
        ->and($info->statusDescription)->toBe('Label created')
        ->and($info->deliveredAt)->toBeNull();
});

it('falls back to pending when UPS reports neither scans nor a current status', function (): void {
    fakeUps(upsResponse(['trackingNumber' => UPS_NUMBER]));

    expect(upsDriver()->track(UPS_NUMBER)->status)->toBe(ShipmentStatus::Pending);
});

it('breaks a UPS rank tie on the scan time rather than the feed order', function (): void {
    fakeUps(upsResponse([
        'trackingNumber' => UPS_NUMBER,
        'activity' => [
            [
                'status' => ['type' => 'X', 'code' => 'RD', 'description' => 'Delivery attempted'],
                'gmtDate' => '20260903',
                'gmtTime' => '10:00:00',
            ],
            [
                'status' => ['type' => 'D', 'code' => 'OF', 'description' => 'Out for delivery'],
                'gmtDate' => '20260904',
                'gmtTime' => '09:00:00',
            ],
        ],
    ]));

    expect(upsDriver()->track(UPS_NUMBER)->status)->toBe(ShipmentStatus::OutForDelivery);
});

it('reuses the UPS access token across separate driver instances', function (): void {
    fakeUps(upsPackage());

    upsDriver()->track(UPS_NUMBER);
    upsDriver()->track(UPS_NUMBER);

    Http::assertSentCount(3);
    Http::assertSent(fn ($request): bool => str_contains($request->url(), '/api/track/v1/details/')
        && $request->hasHeader('Authorization', 'Bearer ups-token'));
});

it('refetches the UPS access token once the cached one has expired', function (): void {
    Http::fake([
        'onlinetools.ups.com/security/v1/oauth/token' => Http::sequence()
            ->push(['access_token' => 'ups-token-1', 'expires_in' => 61])
            ->push(['access_token' => 'ups-token-2', 'expires_in' => 61]),
        'onlinetools.ups.com/api/track/v1/details/*' => Http::response(upsPackage()),
    ]);

    upsDriver()->track(UPS_NUMBER);

    $this->travel(2)->minutes();

    upsDriver()->track(UPS_NUMBER);

    Http::assertSentCount(4);
    Http::assertSent(fn ($request): bool => str_contains($request->url(), '/api/track/v1/details/')
        && $request->hasHeader('Authorization', 'Bearer ups-token-2'));
});

it('sends a UPS transaction id the carrier will accept', function (): void {
    fakeUps(upsPackage());

    upsDriver()->track(UPS_NUMBER);

    Http::assertSent(fn ($request): bool => ! str_contains($request->url(), '/api/track/v1/details/')
        || mb_strlen($request->header('transId')[0]) === 32);
});

it('escapes a UPS tracking number instead of letting it shape the request path', function (): void {
    fakeUps(upsPackage());

    upsDriver()->track('1Z999/../oauth/token');

    Http::assertSent(fn ($request): bool => ! str_contains($request->url(), '/api/track/v1/details/')
        || str_contains($request->url(), '/api/track/v1/details/1Z999%2F..%2Foauth%2Ftoken'));
});

it('tracks against the UPS sandbox host when the driver runs in sandbox mode', function (): void {
    Http::fake([
        'wwwcie.ups.com/security/v1/oauth/token' => Http::response(['access_token' => 'ups-sandbox', 'expires_in' => 14399]),
        'wwwcie.ups.com/api/track/v1/details/*' => Http::response(upsPackage()),
    ]);

    upsDriver(sandbox: true)->track(UPS_NUMBER);

    Http::assertSent(fn ($request): bool => str_starts_with($request->url(), 'https://wwwcie.ups.com/'));
});

it('reports a tracking number UPS has no record of as not found', function (): void {
    fakeUps(['response' => ['errors' => [['code' => 'TW0001', 'message' => 'Tracking Information Not Found']]]], 404);

    expect(fn () => upsDriver()->track(UPS_NUMBER))
        ->toThrow(TrackingNotFoundException::class);
});

it('never reads a bare UPS 404 as a missing parcel', function (): void {
    fakeUps('<html>Not Found</html>', 404);

    expect(fn () => upsDriver()->track(UPS_NUMBER))
        ->toThrow(ShippingException::class, 'API error from [ups]: HTTP 404')
        ->and(fn () => upsDriver()->track(UPS_NUMBER))
        ->not->toThrow(TrackingNotFoundException::class);
});

it('surfaces a UPS outage as a retryable api error', function (): void {
    fakeUps(['response' => ['errors' => [['code' => '250002', 'message' => 'Invalid Authentication Information']]]], 503);

    expect(fn () => upsDriver()->track(UPS_NUMBER))
        ->toThrow(ShippingException::class, 'API error from [ups]: Invalid Authentication Information');
});

it('rejects a UPS response that carries no package', function (): void {
    fakeUps(['trackResponse' => ['shipment' => [['inquiryNumber' => UPS_NUMBER]]]]);

    expect(fn () => upsDriver()->track(UPS_NUMBER))
        ->toThrow(ShippingException::class, 'Invalid response received from [ups] API.');
});

it('rejects a UPS token response that carries no access token', function (): void {
    fakeUps(upsPackage(), token: ['token_type' => 'Bearer', 'expires_in' => 14399]);

    expect(fn () => upsDriver()->track(UPS_NUMBER))
        ->toThrow(ShippingException::class, 'Invalid response received from [ups] API.');

    Http::assertNotSent(fn ($request): bool => str_contains($request->url(), '/api/track/v1/details/'));
});

it('never calls the UPS tracking endpoint when the credentials are refused', function (): void {
    Http::fake([
        'onlinetools.ups.com/security/v1/oauth/token' => Http::response(['response' => ['errors' => [['code' => '10401', 'message' => 'Unauthorized']]]], 401),
        'onlinetools.ups.com/api/track/v1/details/*' => Http::response(upsPackage()),
    ]);

    expect(fn () => upsDriver()->track(UPS_NUMBER))
        ->toThrow(ShippingException::class, 'API error from [ups]: Unauthorized');

    Http::assertNotSent(fn ($request): bool => str_contains($request->url(), '/api/track/v1/details/'));
});

it('turns the FedEx scan events into shipment events keyed on the raw scan code', function (): void {
    fakeFedEx(fedexPackage());

    $info = fedexDriver()->track(FEDEX_NUMBER);

    expect($info->status)->toBe(ShipmentStatus::Delivered)
        ->and($info->statusDescription)->toBe('Delivered')
        ->and($info->deliveredAt?->format('Y-m-d H:i:s e'))->toBe('2026-09-03 19:31:00 UTC')
        ->and($info->estimatedDelivery?->format('Y-m-d H:i:s e'))->toBe('2026-09-04 01:00:00 UTC')
        ->and(eventStatuses($info))->toBe(['delivered', 'in_transit'])
        ->and(eventIds($info))->toBe(['fedex:20260903193100:DL', 'fedex:20260902120400:DP'])
        ->and(collect($info->events)->first()->location)->toBe('MEMPHIS, TN, US');
});

it('records a FedEx delivery even when its own scan cannot be timed', function (): void {
    fakeFedEx(fedexResponse([
        'trackingNumberInfo' => ['trackingNumber' => FEDEX_NUMBER],
        'latestStatusDetail' => ['statusByLocale' => 'Delivered'],
        'dateAndTimes' => [['type' => 'ACTUAL_DELIVERY', 'dateTime' => '2026-09-03T14:31:00-05:00']],
        'scanEvents' => [
            ['date' => '', 'eventType' => 'DL', 'derivedStatusCode' => 'DL', 'eventDescription' => 'Delivered'],
            ['date' => '2026-09-02T07:04:00-05:00', 'eventType' => 'AR', 'derivedStatusCode' => 'AR'],
        ],
    ]));

    $info = fedexDriver()->track(FEDEX_NUMBER);

    expect($info->status)->toBe(ShipmentStatus::Delivered)
        ->and(eventIds($info))->toBe(['fedex:20260902120400:AR', 'fedex:delivered:'.FEDEX_NUMBER])
        ->and($info->deliveredAt?->format('Y-m-d H:i:s e'))->toBe('2026-09-03 19:31:00 UTC');
});

it('derives the FedEx delivered instant from the scan when no delivery date is reported', function (): void {
    fakeFedEx(fedexResponse([
        'trackingNumberInfo' => ['trackingNumber' => FEDEX_NUMBER],
        'latestStatusDetail' => ['statusByLocale' => 'Delivered'],
        'scanEvents' => [
            ['date' => '2026-09-03T14:31:00-05:00', 'eventType' => 'DL', 'derivedStatusCode' => 'DL'],
        ],
    ]));

    $info = fedexDriver()->track(FEDEX_NUMBER);

    expect($info->deliveredAt?->format('Y-m-d H:i:s e'))->toBe('2026-09-03 19:31:00 UTC')
        ->and($info->events)->toHaveCount(1);
});

it('prefers the localized FedEx status over the raw description', function (): void {
    fakeFedEx(fedexResponse([
        'trackingNumberInfo' => ['trackingNumber' => FEDEX_NUMBER],
        'latestStatusDetail' => ['statusByLocale' => 'Livré', 'description' => 'Delivered'],
    ]));

    expect(fedexDriver()->track(FEDEX_NUMBER)->statusDescription)->toBe('Livré');
});

it('reports a FedEx shipment with no scans yet as pending', function (): void {
    fakeFedEx(fedexResponse([
        'trackingNumberInfo' => ['trackingNumber' => FEDEX_NUMBER],
        'latestStatusDetail' => ['description' => 'Label created'],
    ]));

    $info = fedexDriver()->track(FEDEX_NUMBER);

    expect($info->events)->toBe([])
        ->and($info->status)->toBe(ShipmentStatus::Pending)
        ->and($info->statusDescription)->toBe('Label created')
        ->and($info->deliveredAt)->toBeNull();
});

it('tracks against the FedEx sandbox host when the driver runs in sandbox mode', function (): void {
    Http::fake([
        'apis-sandbox.fedex.com/oauth/token' => Http::response(['access_token' => 'fedex-sandbox', 'expires_in' => 3599]),
        'apis-sandbox.fedex.com/track/v1/trackingnumbers' => Http::response(fedexPackage()),
    ]);

    fedexDriver(sandbox: true)->track(FEDEX_NUMBER);

    Http::assertSent(fn ($request): bool => str_starts_with($request->url(), 'https://apis-sandbox.fedex.com/'));
});

it('reports a tracking number FedEx has no record of as not found', function (): void {
    fakeFedEx(fedexResponse([
        'trackingNumberInfo' => ['trackingNumber' => FEDEX_NUMBER],
        'error' => ['code' => 'TRACKING.TRACKINGNUMBER.NOTFOUND', 'message' => 'Invalid tracking numbers.'],
    ]));

    expect(fn () => fedexDriver()->track(FEDEX_NUMBER))
        ->toThrow(TrackingNotFoundException::class);
});

it('surfaces a FedEx result error as a retryable api error', function (): void {
    fakeFedEx(fedexResponse([
        'trackingNumberInfo' => ['trackingNumber' => FEDEX_NUMBER],
        'error' => ['code' => 'SYSTEM.UNEXPECTED.ERROR', 'message' => 'The system is temporarily unavailable.'],
    ]));

    expect(fn () => fedexDriver()->track(FEDEX_NUMBER))
        ->toThrow(ShippingException::class, 'API error from [fedex]: The system is temporarily unavailable.')
        ->and(fn () => fedexDriver()->track(FEDEX_NUMBER))
        ->not->toThrow(TrackingNotFoundException::class);
});

it('surfaces a FedEx http outage as a retryable api error', function (): void {
    fakeFedEx(['output' => ['alerts' => [['message' => 'Service temporarily unavailable']]]], 500);

    expect(fn () => fedexDriver()->track(FEDEX_NUMBER))
        ->toThrow(ShippingException::class, 'API error from [fedex]: Service temporarily unavailable');
});

it('rejects a FedEx response that carries no track result', function (): void {
    fakeFedEx(['output' => ['completeTrackResults' => [['trackResults' => []]]]]);

    expect(fn () => fedexDriver()->track(FEDEX_NUMBER))
        ->toThrow(ShippingException::class, 'Invalid response received from [fedex] API.');
});

it('rejects a FedEx token response that carries no access token', function (): void {
    fakeFedEx(fedexPackage(), token: ['token_type' => 'bearer']);

    expect(fn () => fedexDriver()->track(FEDEX_NUMBER))
        ->toThrow(ShippingException::class, 'Invalid response received from [fedex] API.');

    Http::assertNotSent(fn ($request): bool => str_contains($request->url(), '/track/v1/trackingnumbers'));
});

it('never calls the FedEx tracking endpoint when the credentials are refused', function (): void {
    Http::fake([
        'apis.fedex.com/oauth/token' => Http::response(['error' => 'invalid_client', 'error_description' => 'Bad credentials'], 401),
        'apis.fedex.com/track/v1/trackingnumbers' => Http::response(fedexPackage()),
    ]);

    expect(fn () => fedexDriver()->track(FEDEX_NUMBER))
        ->toThrow(ShippingException::class, 'API error from [fedex]: Bad credentials');

    Http::assertNotSent(fn ($request): bool => str_contains($request->url(), '/track/v1/trackingnumbers'));
});

it('refuses to track before the carrier credentials are configured', function (): void {
    Http::fake();

    expect(fn () => (new UpsDriver('', '', '', ''))->track(UPS_NUMBER))
        ->toThrow(ShippingException::class, 'The [ups] shipping driver is not configured. Please check your .env file.')
        ->and(fn () => (new FedExDriver('', '', ''))->track(FEDEX_NUMBER))
        ->toThrow(ShippingException::class, 'The [fedex] shipping driver is not configured. Please check your .env file.');

    Http::assertNothingSent();
});
