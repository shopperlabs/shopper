<?php

declare(strict_types=1);

namespace Shopper\Shipping\DataTransferObjects;

use DateTimeInterface;
use Shopper\Core\Enum\ShipmentStatus;

final readonly class TrackingEvent
{
    public function __construct(
        public ShipmentStatus $status,
        public ?string $description,
        public DateTimeInterface $occurredAt,
        public ?string $location = null,
        public ?string $externalId = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
    ) {}

    /**
     * @return array<string, string|float|null>
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status->value,
            'description' => $this->description,
            'occurred_at' => $this->occurredAt->format('Y-m-d H:i:s'),
            'location' => $this->location,
            'external_id' => $this->externalId,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
