<?php

declare(strict_types=1);

namespace Shopper\Shipping\Drivers;

use Exception;
use Illuminate\Support\Collection;
use Mitrik\Shipping\ServiceProviders\Address\Address as MitrikAddress;
use Mitrik\Shipping\ServiceProviders\Box\BoxCollection;
use Mitrik\Shipping\ServiceProviders\Box\BoxMetric;
use Mitrik\Shipping\ServiceProviders\ServiceUPS\ServiceUPS;
use Mitrik\Shipping\ServiceProviders\ServiceUPS\ServiceUPSCredentials;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Package;
use Shopper\Shipping\DataTransferObjects\Shipment;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\DataTransferObjects\TrackingInfo;
use Shopper\Shipping\Exceptions\ShippingException;

final class UpsDriver extends Driver
{
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

            return collect($rates)->map(fn ($rate): ShippingRate => new ShippingRate(
                serviceCode: $rate->getServiceCode(),
                serviceName: $rate->getServiceName(),
                amount: (int) ($rate->getPrice() * 100),
                currency: $rate->getCurrency() ?? 'USD',
                carrierCode: 'ups',
                estimatedDays: $rate->getDeliveryEstimate() ?? null,
            ));
        } catch (Exception $e) {
            throw ShippingException::apiError('ups', $e->getMessage());
        }
    }

    public function createShipment(
        Address $from,
        Address $to,
        array $packages,
        string $serviceCode
    ): Shipment {
        if (! $this->isConfigured()) {
            throw ShippingException::notConfigured('ups');
        }

        // Implementation would use ServiceUPS->ship() method
        // This is a placeholder for the full implementation
        throw ShippingException::notSupported('createShipment', 'ups');
    }

    public function track(string $trackingNumber): TrackingInfo
    {
        if (! $this->isConfigured()) {
            throw ShippingException::notConfigured('ups');
        }

        // Implementation would use UPS Tracking API
        throw ShippingException::notSupported('track', 'ups');
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
