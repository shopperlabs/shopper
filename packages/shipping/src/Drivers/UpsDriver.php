<?php

declare(strict_types=1);

namespace Shopper\Shipping\Drivers;

use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Mitrik\Shipping\ServiceProviders\Address\Address as MitrikAddress;
use Mitrik\Shipping\ServiceProviders\Box\BoxCollection;
use Mitrik\Shipping\ServiceProviders\Box\BoxMetric;
use Mitrik\Shipping\ServiceProviders\ServiceUPS\ServiceUPS;
use Mitrik\Shipping\ServiceProviders\ServiceUPS\ServiceUPSCredentials;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Shipping\Concerns\InteractsWithCarrierApi;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Package;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\DataTransferObjects\TrackingEvent;
use Shopper\Shipping\DataTransferObjects\TrackingInfo;
use Shopper\Shipping\Exceptions\ShippingException;
use Shopper\Shipping\Exceptions\TrackingNotFoundException;
use Throwable;

final class UpsDriver extends Driver
{
    use InteractsWithCarrierApi;

    private ?ServiceUPS $client = null;

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

    public function logo(): string
    {
        return shopper_panel_assets('/images/carriers/ups.svg');
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

        $client = $this->getClient();
        $packages = $this->normalizePackages($packages);

        try {
            $rates = $client->rate(
                $this->toMitrikAddress($from),
                $this->toMitrikAddress($to),
                $this->toBoxCollection($packages),
            );

            return collect($rates)->map(function ($rate): ShippingRate {
                $currency = $rate->getCurrency() ?? 'USD';

                return new ShippingRate(
                    serviceCode: $rate->getServiceCode(),
                    serviceName: $rate->getServiceName(),
                    amount: is_no_division_currency($currency)
                        ? (int) $rate->getPrice()
                        : (int) round($rate->getPrice() * 100),
                    currency: $currency,
                    carrierCode: 'ups',
                    estimatedDays: $rate->getDeliveryEstimate() ?? null,
                );
            });
        } catch (Exception $e) {
            throw ShippingException::apiError('ups', $e->getMessage());
        }
    }

    public function track(string $trackingNumber): TrackingInfo
    {
        if (! $this->isConfigured()) {
            throw ShippingException::notConfigured('ups');
        }

        try {
            $response = $this->api()
                ->withToken($this->accessToken())
                ->withHeaders([
                    'transId' => (string) Str::uuid()->getHex(),
                    'transactionSrc' => 'shopper',
                ])
                ->get('/api/track/v1/details/'.rawurlencode($trackingNumber), [
                    'locale' => 'en_US',
                    'returnSignature' => 'false',
                ]);
        } catch (ConnectionException $e) {
            throw ShippingException::apiError('ups', $e->getMessage());
        }

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

    private function getClient(): ServiceUPS
    {
        if ($this->client === null) {
            $credentials = new ServiceUPSCredentials(
                $this->clientId,
                $this->userId,
                $this->clientSecret,
                $this->accountNumber,
                $this->sandbox,
            );

            $this->client = new ServiceUPS($credentials);
        }

        return $this->client;
    }

    private function toMitrikAddress(Address $address): MitrikAddress
    {
        return new MitrikAddress(
            $address->firstName,
            $address->lastName,
            $address->company ?? '',
            $address->street,
            $address->street2 ?? '',
            $address->city,
            $address->postalCode,
            $address->state,
            $address->country,
        );
    }

    /**
     * @param  array<int, Package>  $packages
     */
    private function toBoxCollection(array $packages): BoxCollection
    {
        $boxes = array_map(
            fn (Package $p): BoxMetric => new BoxMetric($p->length, $p->width, $p->height, $p->weight),
            $packages
        );

        return new BoxCollection($boxes);
    }
}
