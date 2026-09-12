<?php

declare(strict_types=1);

namespace Shopper\FedEx;

use Carbon\CarbonImmutable;
use Closure;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Shipping\Concerns\InteractsWithCarrierApi;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Package;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\DataTransferObjects\TrackingEvent;
use Shopper\Shipping\DataTransferObjects\TrackingInfo;
use Shopper\Shipping\Drivers\Driver;
use Shopper\Shipping\Exceptions\ShippingException;
use Shopper\Shipping\Exceptions\TrackingNotFoundException;
use Throwable;

final class FedExDriver extends Driver
{
    use InteractsWithCarrierApi;

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $accountNumber,
        private readonly bool $sandbox = false,
    ) {}

    public function code(): string
    {
        return 'fedex';
    }

    public function name(): string
    {
        return 'FedEx';
    }

    public function logo(): ?string
    {
        return function_exists('shopper_panel_assets')
            ? shopper_panel_assets('/images/carriers/fedex.svg')
            : null;
    }

    public function isConfigured(): bool
    {
        return filled($this->clientId)
            && filled($this->clientSecret)
            && filled($this->accountNumber);
    }

    public function supportsLabels(): bool
    {
        return false;
    }

    public function supportsTracking(): bool
    {
        return true;
    }

    public function calculateRates(Address $from, Address $to, array $packages): Collection
    {
        if (! $this->isConfigured()) {
            throw ShippingException::notConfigured('fedex');
        }

        $response = $this->send(
            fn (PendingRequest $request) => $request->post('/rate/v1/rates/quotes', [
                'accountNumber' => ['value' => $this->accountNumber],
                'requestedShipment' => [
                    'shipper' => ['address' => $this->toFedExAddress($from)],
                    'recipient' => ['address' => $this->toFedExAddress($to)],
                    'pickupType' => 'DROPOFF_AT_FEDEX_LOCATION',
                    'packagingType' => 'YOUR_PACKAGING',
                    'rateRequestType' => ['ACCOUNT', 'LIST'],
                    'requestedPackageLineItems' => $this->toFedExPackages($this->normalizePackages($packages)),
                ],
            ])
        );

        if ($response->failed()) {
            throw ShippingException::apiError('fedex', $this->carrierMessage($response));
        }

        $details = $response->json('output.rateReplyDetails');

        if (! is_array($details)) {
            throw ShippingException::invalidResponse('fedex');
        }

        $rates = collect($details)
            ->map(fn (mixed $detail): ?ShippingRate => is_array($detail) ? $this->toShippingRate($detail) : null)
            ->filter()
            ->values();

        if ($rates->isEmpty()) {
            throw ShippingException::invalidResponse('fedex');
        }

        return $rates;
    }

    public function track(string $trackingNumber): TrackingInfo
    {
        if (! $this->isConfigured()) {
            throw ShippingException::notConfigured('fedex');
        }

        $response = $this->send(
            fn (PendingRequest $request) => $request->post('/track/v1/trackingnumbers', [
                'includeDetailedScans' => true,
                'trackingInfo' => [
                    ['trackingNumberInfo' => ['trackingNumber' => $trackingNumber]],
                ],
            ])
        );

        if ($response->failed()) {
            throw ShippingException::apiError('fedex', $this->carrierMessage($response));
        }

        $result = $response->json('output.completeTrackResults.0.trackResults.0');

        if (! is_array($result)) {
            throw ShippingException::invalidResponse('fedex');
        }

        $this->failOnResultError($trackingNumber, $result);

        return $this->toTrackingInfo($trackingNumber, $result);
    }

    private function api(): PendingRequest
    {
        return Http::baseUrl($this->sandbox ? 'https://apis-sandbox.fedex.com' : 'https://apis.fedex.com')
            ->acceptJson()
            ->timeout(15)
            ->retry(3, 200, fn (Throwable $e): bool => $e instanceof ConnectionException, throw: false);
    }

    /**
     * @param  Closure(PendingRequest): Response  $call
     */
    private function send(Closure $call): Response
    {
        try {
            return $call($this->api()->withToken($this->accessToken()));
        } catch (ConnectionException $e) {
            throw ShippingException::apiError('fedex', $e->getMessage());
        }
    }

    private function accessToken(): string
    {
        $key = 'fedex:'.sha1($this->clientId.'|'.($this->sandbox ? 'test' : 'live'));

        return $this->cachedToken($key, fn (): array => $this->readToken(
            $this->api()->asForm()->post('/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ])
        ));
    }

    /**
     * @param  array<string, mixed>  $result
     */
    private function failOnResultError(string $trackingNumber, array $result): void
    {
        $code = $this->carrierText($result, 'error.code');

        if ($code === null) {
            return;
        }

        if (str_contains($code, 'NOTFOUND')) {
            throw TrackingNotFoundException::for('fedex', $trackingNumber);
        }

        throw ShippingException::apiError('fedex', $this->carrierText($result, 'error.message') ?? $code);
    }

    /**
     * @param  array<string, mixed>  $result
     */
    private function toTrackingInfo(string $trackingNumber, array $result): TrackingInfo
    {
        $events = collect(data_get($result, 'scanEvents', []))
            ->map(fn (mixed $scan): ?TrackingEvent => is_array($scan) ? $this->toTrackingEvent($scan) : null)
            ->filter()
            ->values();

        $delivered = $events->first(fn (TrackingEvent $event): bool => $event->status === ShipmentStatus::Delivered);

        $deliveredAt = $this->carrierDate($result, 'ACTUAL_DELIVERY')
            ?? ($delivered !== null ? CarbonImmutable::instance($delivered->occurredAt) : null);

        if ($deliveredAt !== null && $delivered === null) {
            $events->push(new TrackingEvent(
                status: ShipmentStatus::Delivered,
                description: $this->latestDescription($result),
                occurredAt: $deliveredAt,
                externalId: 'fedex:delivered:'.$trackingNumber,
            ));
        }

        return new TrackingInfo(
            trackingNumber: $trackingNumber,
            status: $this->highestStatus($events),
            statusDescription: $this->latestDescription($result),
            estimatedDelivery: $this->carrierDate($result, 'ESTIMATED_DELIVERY'),
            deliveredAt: $deliveredAt,
            events: $events->all(),
        );
    }

    /**
     * @param  array<string, mixed>  $scan
     */
    private function toTrackingEvent(array $scan): ?TrackingEvent
    {
        $occurredAt = $this->instant(data_get($scan, 'date'));

        if ($occurredAt === null) {
            return null;
        }

        $type = $this->carrierText($scan, 'eventType') ?? '';

        return new TrackingEvent(
            status: $this->toStatus($this->carrierText($scan, 'derivedStatusCode') ?? $type),
            description: $this->carrierText($scan, 'eventDescription') ?? $this->carrierText($scan, 'derivedStatus'),
            occurredAt: $occurredAt,
            location: $this->toLocation($scan),
            externalId: 'fedex:'.$occurredAt->format('YmdHis').':'.$type,
        );
    }

    private function toStatus(string $code): ShipmentStatus
    {
        return match ($code) {
            'OC' => ShipmentStatus::Pending,
            'PU' => ShipmentStatus::PickedUp,
            'AR' => ShipmentStatus::AtSortingCenter,
            'OD' => ShipmentStatus::OutForDelivery,
            'DL' => ShipmentStatus::Delivered,
            'DE', 'SE' => ShipmentStatus::DeliveryFailed,
            'RS' => ShipmentStatus::Returned,
            default => ShipmentStatus::InTransit,
        };
    }

    /**
     * @param  array<string, mixed>  $scan
     */
    private function toLocation(array $scan): ?string
    {
        return collect([
            $this->carrierText($scan, 'scanLocation.city'),
            $this->carrierText($scan, 'scanLocation.stateOrProvinceCode'),
            $this->carrierText($scan, 'scanLocation.countryCode'),
        ])->filter()->implode(', ') ?: null;
    }

    /**
     * @param  array<string, mixed>  $result
     */
    private function latestDescription(array $result): ?string
    {
        return $this->carrierText($result, 'latestStatusDetail.statusByLocale')
            ?? $this->carrierText($result, 'latestStatusDetail.description');
    }

    /**
     * @param  array<string, mixed>  $result
     */
    private function carrierDate(array $result, string $type): ?CarbonImmutable
    {
        $entry = collect(data_get($result, 'dateAndTimes', []))->firstWhere('type', $type);

        return $this->instant(data_get($entry, 'dateTime'));
    }

    private function instant(mixed $value): ?CarbonImmutable
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        try {
            return CarbonImmutable::parse($value)->utc();
        } catch (Exception) {
            return null;
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function toFedExAddress(Address $address): array
    {
        return [
            'streetLines' => collect([$address->street, $address->street2])->filter()->values()->all(),
            'city' => $address->city,
            'stateOrProvinceCode' => $address->state,
            'postalCode' => $address->postalCode,
            'countryCode' => $address->country,
        ];
    }

    /**
     * @param  array<int, Package>  $packages
     * @return array<int, array<string, mixed>>
     */
    private function toFedExPackages(array $packages): array
    {
        return array_map(fn (Package $package): array => [
            'weight' => [
                'units' => 'KG',
                'value' => round($package->weight, 2),
            ],
            'dimensions' => [
                'length' => round($package->length, 2),
                'width' => round($package->width, 2),
                'height' => round($package->height, 2),
                'units' => 'CM',
            ],
        ], $packages);
    }

    /**
     * @param  array<string, mixed>  $detail
     */
    private function toShippingRate(array $detail): ?ShippingRate
    {
        $code = $this->carrierText($detail, 'serviceType');
        $rated = $this->ratedShipment($detail);

        if ($code === null || $rated === null) {
            return $this->dropRate($detail, 'service');
        }

        $amount = data_get($rated, 'totalNetCharge');
        $currency = $this->carrierText($rated, 'currency')
            ?? $this->carrierText($rated, 'shipmentRateDetail.currency');

        if ($currency === null || ! is_numeric($amount)) {
            return $this->dropRate($detail, 'charges');
        }

        $currency = mb_strtoupper($currency);

        $minorUnits = is_no_division_currency($currency)
            ? (int) round((float) $amount)
            : (int) round((float) $amount * 100);

        if ($minorUnits <= 0) {
            return $this->dropRate($detail, 'amount');
        }

        return new ShippingRate(
            serviceCode: $code,
            serviceName: $this->carrierText($detail, 'serviceName') ?? $code,
            amount: $minorUnits,
            currency: $currency,
            carrierCode: 'fedex',
        );
    }

    /**
     * @param  array<string, mixed>  $detail
     * @return array<string, mixed>|null
     */
    private function ratedShipment(array $detail): ?array
    {
        $rated = collect(data_get($detail, 'ratedShipmentDetails'))
            ->filter(fn (mixed $entry): bool => is_array($entry));

        return $rated->firstWhere('rateType', 'ACCOUNT') ?? $rated->first();
    }

    /**
     * @param  array<string, mixed>  $detail
     */
    private function dropRate(array $detail, string $reason): null
    {
        Log::warning('Discarded an unusable FedEx rate.', [
            'driver' => 'fedex',
            'service_type' => $this->carrierText($detail, 'serviceType'),
            'reason' => $reason,
        ]);

        return null;
    }
}
