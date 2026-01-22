<?php

declare(strict_types=1);

namespace Shopper\Shipping\DataTransferObjects;

use DateTimeInterface;

final readonly class TrackingInfo
{
    /**
     * @param  array<int, TrackingEvent>  $events
     */
    public function __construct(
        public string $trackingNumber,
        public string $status,
        public ?string $statusDescription = null,
        public ?DateTimeInterface $estimatedDelivery = null,
        public ?DateTimeInterface $deliveredAt = null,
        public array $events = [],
    ) {}

    public function isDelivered(): bool
    {
        return $this->deliveredAt !== null || $this->status === 'delivered';
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'tracking_number' => $this->trackingNumber,
            'status' => $this->status,
            'status_description' => $this->statusDescription,
            'estimated_delivery' => $this->estimatedDelivery?->format('Y-m-d H:i:s'),
            'delivered_at' => $this->deliveredAt?->format('Y-m-d H:i:s'),
            'events' => array_map(fn (TrackingEvent $event): array => $event->toArray(), $this->events),
        ];
    }
}
