<?php

declare(strict_types=1);

namespace Shopper\Shipping\Contracts;

use Illuminate\Support\Collection;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Package;
use Shopper\Shipping\DataTransferObjects\Shipment;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\DataTransferObjects\TrackingInfo;

interface ShippingDriver
{
    public function code(): string;

    public function name(): string;

    public function logo(): ?string;

    public function isConfigured(): bool;

    public function supportsRealTimeRates(): bool;

    public function supportsLabels(): bool;

    public function supportsTracking(): bool;

    /**
     * Calculate shipping rates.
     *
     * @param  array<int, Package>  $packages
     * @return Collection<int, ShippingRate>
     */
    public function calculateRates(Address $from, Address $to, array $packages): Collection;

    /**
     * Create a shipment and get label.
     *
     * @param  array<int, Package>  $packages
     */
    public function createShipment(
        Address $from,
        Address $to,
        array $packages,
        string $serviceCode
    ): Shipment;

    /**
     * Track a shipment by tracking number.
     */
    public function track(string $trackingNumber): TrackingInfo;
}
