<?php

declare(strict_types=1);

namespace Shopper\Shipping\Drivers;

use Exception;
use Illuminate\Support\Collection;
use Mitrik\Shipping\ServiceProviders\Address\Address as MitrikAddress;
use Mitrik\Shipping\ServiceProviders\Box\BoxCollection;
use Mitrik\Shipping\ServiceProviders\Box\BoxImperial;
use Mitrik\Shipping\ServiceProviders\ServiceUSPS\ServiceUSPS;
use Mitrik\Shipping\ServiceProviders\ServiceUSPS\ServiceUSPSCredentials;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Package;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\Exceptions\ShippingException;

final class UspsDriver extends Driver
{
    private ?ServiceUSPS $client = null;

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly bool $sandbox = false,
    ) {}

    public function code(): string
    {
        return 'usps';
    }

    public function name(): string
    {
        return 'USPS';
    }

    public function logo(): string
    {
        return shopper_panel_assets('/images/carriers/usps.svg');
    }

    public function supportsLabels(): bool
    {
        return false;
    }

    public function supportsTracking(): bool
    {
        return false;
    }

    public function isConfigured(): bool
    {
        return ! empty($this->clientId)
            && ! empty($this->clientSecret);
    }

    public function calculateRates(Address $from, Address $to, array $packages): Collection
    {
        if (! $this->isConfigured()) {
            throw ShippingException::notConfigured('usps');
        }

        $client = $this->getClient();
        // USPS uses imperial units
        $packages = $this->normalizePackages($packages, 'imperial');

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
                currency: 'USD',
                carrierCode: 'usps',
                estimatedDays: $rate->getDeliveryEstimate() ?? null,
            ));
        } catch (Exception $e) {
            throw ShippingException::apiError('usps', $e->getMessage());
        }
    }

    private function getClient(): ServiceUSPS
    {
        if ($this->client === null) {
            $credentials = new ServiceUSPSCredentials(
                $this->clientId,
                $this->clientSecret,
                $this->sandbox,
            );

            $this->client = new ServiceUSPS($credentials);
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
            fn (Package $p): BoxImperial => new BoxImperial($p->length, $p->width, $p->height, $p->weight),
            $packages
        );

        return new BoxCollection($boxes);
    }
}
