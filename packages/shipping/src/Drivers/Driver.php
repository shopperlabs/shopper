<?php

declare(strict_types=1);

namespace Shopper\Shipping\Drivers;

use Shopper\Shipping\Contracts\ShippingDriver;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Package;
use Shopper\Shipping\DataTransferObjects\Shipment;
use Shopper\Shipping\DataTransferObjects\TrackingInfo;
use Shopper\Shipping\Exceptions\ShippingException;

abstract class Driver implements ShippingDriver
{
    public function logo(): ?string
    {
        return null;
    }

    public function supportsRealTimeRates(): bool
    {
        return true;
    }

    public function supportsLabels(): bool
    {
        return true;
    }

    public function supportsTracking(): bool
    {
        return true;
    }

    public function createShipment(
        Address $from,
        Address $to,
        array $packages,
        string $serviceCode
    ): Shipment {
        throw ShippingException::notSupported('createShipment', $this->code());
    }

    public function track(string $trackingNumber): TrackingInfo
    {
        throw ShippingException::notSupported('track', $this->code());
    }

    /**
     * Convert package to metric units if needed.
     *
     * @param  array<int, Package>  $packages
     * @return array<int, Package>
     */
    protected function normalizePackages(array $packages, string $targetUnit = 'metric'): array
    {
        return array_map(function (Package $package) use ($targetUnit): Package {
            if ($package->unit === $targetUnit) {
                return $package;
            }

            if ($targetUnit === 'metric' && $package->isImperial()) {
                return new Package(
                    length: $package->length * 2.54, // in to cm
                    width: $package->width * 2.54,
                    height: $package->height * 2.54,
                    weight: $package->weight * 0.453592, // lb to kg
                    unit: 'metric',
                );
            }

            if ($targetUnit === 'imperial' && $package->isMetric()) {
                return new Package(
                    length: $package->length / 2.54, // cm to in
                    width: $package->width / 2.54,
                    height: $package->height / 2.54,
                    weight: $package->weight / 0.453592, // kg to lb
                    unit: 'imperial',
                );
            }

            return $package;
        }, $packages);
    }
}
