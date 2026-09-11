<?php

declare(strict_types=1);

namespace Shopper\Shipping\Drivers;

use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Mitrik\Shipping\ServiceProviders\Address\Address as MitrikAddress;
use Mitrik\Shipping\ServiceProviders\Box\BoxCollection;
use Mitrik\Shipping\ServiceProviders\Box\BoxMetric;
use Mitrik\Shipping\ServiceProviders\ServiceFedEx\ServiceFedEx;
use Mitrik\Shipping\ServiceProviders\ServiceFedEx\ServiceFedExCredentials;
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

final class FedExDriver extends Driver
{
    use InteractsWithCarrierApi;

    private ?ServiceFedEx $client = null;

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

    public function logo(): string
    {
        return shopper_panel_assets('/images/carriers/fedex.svg');
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
                    carrierCode: 'fedex',
                    estimatedDays: $rate->getDeliveryEstimate() ?? null,
                );
            });
        } catch (Exception $e) {
            throw ShippingException::apiError('fedex', $e->getMessage());
        }
    }

    public function track(string $trackingNumber): TrackingInfo
    {
        if (! $this->isConfigured()) {
            throw ShippingException::notConfigured('fedex');
        }

        try {
            $response = $this->api()
                ->withToken($this->accessToken())
                ->post('/track/v1/trackingnumbers', [
                    'includeDetailedScans' => true,
                    'trackingInfo' => [
                        ['trackingNumberInfo' => ['trackingNumber' => $trackingNumber]],
                    ],
                ]);
        } catch (ConnectionException $e) {
            throw ShippingException::apiError('fedex', $e->getMessage());
        }

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

    private function getClient(): ServiceFedEx
    {
        if ($this->client === null) {
            $credentials = new ServiceFedExCredentials(
                $this->clientId,
                $this->clientSecret,
                $this->accountNumber,
                $this->sandbox,
            );

            $this->client = new ServiceFedEx($credentials);
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
