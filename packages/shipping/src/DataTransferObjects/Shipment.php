<?php

declare(strict_types=1);

namespace Shopper\Shipping\DataTransferObjects;

final readonly class Shipment
{
    public function __construct(
        public string $trackingNumber,
        public string $carrierCode,
        public string $serviceCode,
        public ?string $labelUrl = null,
        public ?string $labelData = null, // Base64 encoded label
        public ?string $labelFormat = null, // PDF, PNG, ZPL, etc.
    ) {}

    public function hasLabel(): bool
    {
        return $this->labelUrl !== null || $this->labelData !== null;
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'tracking_number' => $this->trackingNumber,
            'carrier_code' => $this->carrierCode,
            'service_code' => $this->serviceCode,
            'label_url' => $this->labelUrl,
            'label_format' => $this->labelFormat,
        ];
    }
}
