<?php

declare(strict_types=1);

namespace Shopper\Shipping\Drivers;

use Exception;
use Illuminate\Support\Collection;
use Mitrik\Shipping\ServiceProviders\Address\Address as MitrikAddress;
use Mitrik\Shipping\ServiceProviders\Box\BoxCollection;
use Mitrik\Shipping\ServiceProviders\Box\BoxMetric;
use Mitrik\Shipping\ServiceProviders\ServiceFedEx\ServiceFedEx;
use Mitrik\Shipping\ServiceProviders\ServiceFedEx\ServiceFedExCredentials;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Package;
use Shopper\Shipping\DataTransferObjects\Shipment;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\DataTransferObjects\TrackingInfo;
use Shopper\Shipping\Exceptions\ShippingException;

final class FedExDriver extends Driver
{
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

    public function createShipment(
        Address $from,
        Address $to,
        array $packages,
        string $serviceCode
    ): Shipment {
        if (! $this->isConfigured()) {
            throw ShippingException::notConfigured('fedex');
        }

        throw ShippingException::notSupported('createShipment', 'fedex');
    }

    public function track(string $trackingNumber): TrackingInfo
    {
        if (! $this->isConfigured()) {
            throw ShippingException::notConfigured('fedex');
        }

        throw ShippingException::notSupported('track', 'fedex');
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
