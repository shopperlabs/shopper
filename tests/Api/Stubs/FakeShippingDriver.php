<?php

declare(strict_types=1);

namespace Tests\Api\Stubs;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use RuntimeException;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Shipping\Contracts\ShippingDriver;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Shipment;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\DataTransferObjects\TrackingEvent;
use Shopper\Shipping\DataTransferObjects\TrackingInfo;
use Shopper\Shipping\Exceptions\ShippingException;

final class FakeShippingDriver implements ShippingDriver
{
    public int $calls = 0;

    public int $trackings = 0;

    /**
     * @param  array<int, ShippingRate>  $rates
     */
    public function __construct(
        private readonly array $rates = [],
        private readonly bool $fails = false,
        private readonly bool $configured = true,
        private readonly bool $webhooks = false,
        private readonly ?TrackingInfo $tracking = null,
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
        return $this->configured;
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
        return $this->tracking !== null;
    }

    public function supportsWebhooks(): bool
    {
        return $this->webhooks;
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
        $this->trackings++;

        if ($this->fails || $this->tracking === null) {
            throw ShippingException::apiError('fake', 'Tracking unavailable');
        }

        return $this->tracking;
    }

    public function handleWebhook(Request $request): ?TrackingInfo
    {
        if ($request->input('signature') === 'invalid') {
            throw new RuntimeException('Invalid webhook signature.');
        }

        if (! $request->filled('tracking_number')) {
            return null;
        }

        $events = array_map(fn (array $event): TrackingEvent => new TrackingEvent(
            status: ShipmentStatus::from($event['status']),
            description: $event['description'] ?? null,
            occurredAt: Carbon::parse($event['occurred_at']),
            location: $event['location'] ?? null,
            externalId: $event['id'] ?? null,
        ), $request->input('events', []));

        return new TrackingInfo(
            trackingNumber: (string) $request->input('tracking_number'),
            status: ShipmentStatus::from($request->input('status')),
            events: $events,
        );
    }
}
