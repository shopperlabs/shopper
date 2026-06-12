<?php

declare(strict_types=1);

namespace Tests\Api\Stubs;

use Illuminate\Support\Collection;
use Shopper\Shipping\Contracts\ShippingDriver;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Shipment;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\DataTransferObjects\TrackingInfo;
use Shopper\Shipping\Exceptions\ShippingException;

final class FakeShippingDriver implements ShippingDriver
{
    public int $calls = 0;

    /**
     * @param  array<int, ShippingRate>  $rates
     */
    public function __construct(
        private readonly array $rates = [],
        private readonly bool $fails = false,
    ) {}

    public function code(): string
    {
        return 'fake';
    }

    public function name(): string
    {
        return 'Fake Carrier';
    }

    public function logo(): ?string
    {
        return null;
    }

    public function isConfigured(): bool
    {
        return true;
    }

    public function supportsRealTimeRates(): bool
    {
        return true;
    }

    public function supportsLabels(): bool
    {
        return false;
    }

    public function supportsTracking(): bool
    {
        return false;
    }

    public function calculateRates(Address $from, Address $to, array $packages): Collection
    {
        $this->calls++;

        if ($this->fails) {
            throw ShippingException::apiError('fake', 'Service unavailable');
        }

        return collect($this->rates);
    }

    public function createShipment(Address $from, Address $to, array $packages, string $serviceCode): Shipment
    {
        throw ShippingException::notSupported('createShipment', 'fake');
    }

    public function track(string $trackingNumber): TrackingInfo
    {
        throw ShippingException::notSupported('track', 'fake');
    }
}
