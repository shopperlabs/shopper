<?php

declare(strict_types=1);

namespace Shopper\Ups;

use Carbon\CarbonImmutable;
use Closure;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

final class UpsDriver extends Driver
{
    use InteractsWithCarrierApi;

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $userId,
        private readonly string $accountNumber,
        private readonly bool $sandbox = false,
    ) {}

    public function code(): string
    {
        return 'ups';
    }

    public function name(): string
    {
        return 'UPS';
    }

    public function logo(): ?string
    {
        return function_exists('shopper_panel_assets')
            ? shopper_panel_assets('/images/carriers/ups.svg')
            : null;
    }

    public function isConfigured(): bool
    {
        return filled($this->clientId)
            && filled($this->clientSecret)
            && filled($this->userId)
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
            throw ShippingException::notConfigured('ups');
        }

        $response = $this->send(
            fn (PendingRequest $request) => $request->post('/api/rating/v2409/Shop', [
                'RateRequest' => [
                    'PickupType' => ['Code' => '01'],
                    'CustomerClassification' => ['Code' => '01'],
                    'Shipment' => [
                        'Shipper' => [
                            'Name' => $this->shipperName($from),
                            'ShipperNumber' => $this->accountNumber,
                            'Address' => $this->toUpsAddress($from),
                        ],
                        'ShipFrom' => ['Address' => $this->toUpsAddress($from)],
                        'ShipTo' => ['Address' => $this->toUpsAddress($to)],
                        'Package' => $this->toUpsPackages($this->normalizePackages($packages)),
                    ],
                ],
            ])
        );

        if ($response->failed()) {
            throw ShippingException::apiError('ups', $this->carrierMessage($response));
        }

        $shipments = $response->json('RateResponse.RatedShipment');

        if (! is_array($shipments)) {
            throw ShippingException::invalidResponse('ups');
        }

        $rates = collect(array_is_list($shipments) ? $shipments : [$shipments])
            ->map(fn (mixed $shipment): ?ShippingRate => is_array($shipment) ? $this->toShippingRate($shipment) : null)
            ->filter()
            ->values();

        if ($rates->isEmpty()) {
            throw ShippingException::invalidResponse('ups');
        }

        return $rates;
    }

    public function track(string $trackingNumber): TrackingInfo
    {
        if (! $this->isConfigured()) {
            throw ShippingException::notConfigured('ups');
        }

        $response = $this->send(
            fn (PendingRequest $request) => $request->get('/api/track/v1/details/'.rawurlencode($trackingNumber), [
                'locale' => 'en_US',
                'returnSignature' => 'false',
            ])
        );

        if ($this->deniesTracking($response)) {
            throw TrackingNotFoundException::for('ups', $trackingNumber);
        }

        if ($response->failed()) {
            throw ShippingException::apiError('ups', $this->carrierMessage($response));
        }

        $package = $response->json('trackResponse.shipment.0.package.0');

        if (! is_array($package)) {
            throw ShippingException::invalidResponse('ups');
        }

        return $this->toTrackingInfo($trackingNumber, $package);
    }

    private function api(): PendingRequest
    {
        return Http::baseUrl($this->sandbox ? 'https://wwwcie.ups.com' : 'https://onlinetools.ups.com')
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
            return $call(
                $this->api()
                    ->withToken($this->accessToken())
                    ->withHeaders([
                        'transId' => (string) Str::uuid()->getHex(),
                        'transactionSrc' => 'shopper',
                    ])
            );
        } catch (ConnectionException $e) {
            throw ShippingException::apiError('ups', $e->getMessage());
        }
    }

    private function accessToken(): string
    {
        $key = 'ups:'.sha1($this->clientId.'|'.($this->sandbox ? 'test' : 'live'));

        return $this->cachedToken($key, fn (): array => $this->readToken(
            $this->api()
                ->asForm()
                ->withBasicAuth($this->clientId, $this->clientSecret)
                ->post('/security/v1/oauth/token', ['grant_type' => 'client_credentials'])
        ));
    }

    private function deniesTracking(Response $response): bool
    {
        return $response->notFound() && $this->carrierText($response->json(), 'response.errors.0.code') !== null;
    }

    /**
     * @param  array<string, mixed>  $package
     */
    private function toTrackingInfo(string $trackingNumber, array $package): TrackingInfo
    {
        $events = collect(data_get($package, 'activity', []))
            ->map(fn (mixed $activity): ?TrackingEvent => is_array($activity) ? $this->toTrackingEvent($activity) : null)
            ->filter()
            ->values();

        $deliveredAt = $this->deliveredAt($package);

        if ($deliveredAt !== null) {
            $events->push(new TrackingEvent(
                status: ShipmentStatus::Delivered,
                description: $this->carrierText($package, 'currentStatus.description'),
                occurredAt: $deliveredAt,
                externalId: 'ups:delivered:'.$trackingNumber,
            ));
        }

        $current = $this->carrierText($package, 'currentStatus.type');

        return new TrackingInfo(
            trackingNumber: $trackingNumber,
            status: match (true) {
                $events->isNotEmpty() => $this->highestStatus($events),
                $current !== null => $this->toStatus($current),
                default => ShipmentStatus::Pending,
            },
            statusDescription: $this->carrierText($package, 'currentStatus.description'),
            estimatedDelivery: $this->carrierDate($package, 'RDD') ?? $this->carrierDate($package, 'SDD'),
            deliveredAt: $deliveredAt,
            events: $events->all(),
        );
    }

    /**
     * @param  array<string, mixed>  $activity
     */
    private function toTrackingEvent(array $activity): ?TrackingEvent
    {
        $occurredAt = $this->instantOf($activity);

        if ($occurredAt === null) {
            return null;
        }

        $type = $this->carrierText($activity, 'status.type') ?? '';
        $code = $this->carrierText($activity, 'status.code') ?? $type;

        return new TrackingEvent(
            status: $this->toStatus($type),
            description: $this->carrierText($activity, 'status.description'),
            occurredAt: $occurredAt,
            location: $this->toLocation($activity),
            externalId: 'ups:'.$occurredAt->format('YmdHis').':'.$code,
        );
    }

    private function toStatus(string $type): ShipmentStatus
    {
        return match ($type) {
            'M' => ShipmentStatus::Pending,
            'P' => ShipmentStatus::PickedUp,
            'D' => ShipmentStatus::OutForDelivery,
            'X' => ShipmentStatus::DeliveryFailed,
            default => ShipmentStatus::InTransit,
        };
    }

    /**
     * @param  array<string, mixed>  $activity
     */
    private function instantOf(array $activity): ?CarbonImmutable
    {
        $gmt = $this->stamp($activity, 'gmtDate', 'gmtTime');

        if ($gmt !== null) {
            return CarbonImmutable::createFromFormat('YmdHis', $gmt, 'UTC');
        }

        $local = $this->stamp($activity, 'date', 'time');
        $offset = $this->carrierText($activity, 'gmtOffset');

        if ($local === null || $offset === null) {
            return null;
        }

        try {
            return CarbonImmutable::parse(mb_substr($local, 0, 8).'T'.mb_substr($local, 8).$offset)->utc();
        } catch (Exception) {
            return null;
        }
    }

    /**
     * @param  array<string, mixed>  $activity
     */
    private function stamp(array $activity, string $dateKey, string $timeKey): ?string
    {
        $stamp = preg_replace('/\D/', '', ($this->carrierText($activity, $dateKey) ?? '').($this->carrierText($activity, $timeKey) ?? ''));

        return is_string($stamp) && mb_strlen($stamp) === 14 ? $stamp : null;
    }

    /**
     * @param  array<string, mixed>  $activity
     */
    private function toLocation(array $activity): ?string
    {
        return collect([
            $this->carrierText($activity, 'location.address.city'),
            $this->carrierText($activity, 'location.address.stateProvince'),
            $this->carrierText($activity, 'location.address.countryCode'),
        ])->filter()->implode(', ') ?: null;
    }

    /**
     * @param  array<string, mixed>  $package
     */
    private function deliveredAt(array $package): ?CarbonImmutable
    {
        $date = $this->carrierDateString($package, 'DEL');

        if ($date === null) {
            return null;
        }

        return collect(data_get($package, 'activity', []))
            ->map(fn (mixed $activity): ?CarbonImmutable => is_array($activity)
                && $this->carrierText($activity, 'date') === $date
                    ? $this->instantOf($activity)
                    : null)
            ->filter()
            ->sort()
            ->last();
    }

    /**
     * @param  array<string, mixed>  $package
     */
    private function carrierDate(array $package, string $type): ?CarbonImmutable
    {
        $date = $this->carrierDateString($package, $type);

        return $date === null
            ? null
            : CarbonImmutable::createFromFormat('Ymd', $date, 'UTC')?->startOfDay();
    }

    /**
     * @param  array<string, mixed>  $package
     */
    private function carrierDateString(array $package, string $type): ?string
    {
        $entry = collect(data_get($package, 'deliveryDate', []))->firstWhere('type', $type);
        $date = $this->carrierText($entry, 'date');

        return $date !== null && CarbonImmutable::canBeCreatedFromFormat($date, 'Ymd') ? $date : null;
    }

    /**
     * @return array<string, mixed>
     */
    private function toUpsAddress(Address $address): array
    {
        return [
            'AddressLine' => collect([$address->street, $address->street2])->filter()->values()->all(),
            'City' => $address->city,
            'StateProvinceCode' => $address->state,
            'PostalCode' => $address->postalCode,
            'CountryCode' => $address->country,
        ];
    }

    private function shipperName(Address $address): string
    {
        return $address->company ?? $address->fullName();
    }

    /**
     * @param  array<int, Package>  $packages
     * @return array<int, array<string, mixed>>
     */
    private function toUpsPackages(array $packages): array
    {
        return array_map(fn (Package $package): array => [
            'PackagingType' => ['Code' => '02'],
            'Dimensions' => [
                'UnitOfMeasurement' => ['Code' => 'CM'],
                'Length' => (string) round($package->length, 2),
                'Width' => (string) round($package->width, 2),
                'Height' => (string) round($package->height, 2),
            ],
            'PackageWeight' => [
                'UnitOfMeasurement' => ['Code' => 'KGS'],
                'Weight' => (string) round($package->weight, 2),
            ],
        ], $packages);
    }

    /**
     * @param  array<string, mixed>  $shipment
     */
    private function toShippingRate(array $shipment): ?ShippingRate
    {
        $code = $this->carrierText($shipment, 'Service.Code');
        $amount = $this->carrierText($shipment, 'TotalCharges.MonetaryValue');
        $currency = $this->carrierText($shipment, 'TotalCharges.CurrencyCode');

        if ($code === null || $currency === null || $amount === null || ! is_numeric($amount)) {
            return $this->dropRate($shipment, 'charges');
        }

        $currency = mb_strtoupper($currency);

        $minorUnits = is_no_division_currency($currency)
            ? (int) round((float) $amount)
            : (int) round((float) $amount * 100);

        if ($minorUnits <= 0) {
            return $this->dropRate($shipment, 'amount');
        }

        return new ShippingRate(
            serviceCode: $code,
            serviceName: $this->carrierText($shipment, 'Service.Description') ?? $this->serviceName($code),
            amount: $minorUnits,
            currency: $currency,
            carrierCode: 'ups',
            estimatedDays: $this->carrierText($shipment, 'GuaranteedDelivery.BusinessDaysInTransit'),
        );
    }

    /**
     * @param  array<string, mixed>  $shipment
     */
    private function dropRate(array $shipment, string $reason): null
    {
        Log::warning('Discarded an unusable UPS rate.', [
            'driver' => 'ups',
            'service_code' => $this->carrierText($shipment, 'Service.Code'),
            'reason' => $reason,
        ]);

        return null;
    }

    private function serviceName(string $code): string
    {
        return match ($code) {
            '01' => 'UPS Next Day Air',
            '02' => 'UPS 2nd Day Air',
            '03' => 'UPS Ground',
            '07' => 'UPS Worldwide Express',
            '08' => 'UPS Worldwide Expedited',
            '11' => 'UPS Standard',
            '12' => 'UPS 3 Day Select',
            '13' => 'UPS Next Day Air Saver',
            '14' => 'UPS Next Day Air Early',
            '54' => 'UPS Worldwide Express Plus',
            '59' => 'UPS 2nd Day Air A.M.',
            '65' => 'UPS Saver',
            '71' => 'UPS Worldwide Express Freight Midday',
            '75' => 'UPS Heavy Goods',
            '96' => 'UPS Worldwide Express Freight',
            default => 'UPS '.$code,
        };
    }
}
