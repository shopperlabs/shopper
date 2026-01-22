<?php

declare(strict_types=1);

namespace Shopper\Shipping\DataTransferObjects;

use Illuminate\Support\Number;

final readonly class ShippingRate
{
    public function __construct(
        public string|int $serviceCode,
        public string $serviceName,
        public int $amount,
        public string $currency,
        public string $carrierCode,
        public ?string $estimatedDays = null,
        public ?string $estimatedDelivery = null,
    ) {}

    public function formattedAmount(): string
    {
        return Number::format($this->amount / 100, 2);
    }

    /**
     * @return array<string, string|int|null>
     */
    public function toArray(): array
    {
        return [
            'service_code' => $this->serviceCode,
            'service_name' => $this->serviceName,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'carrier_code' => $this->carrierCode,
            'estimated_days' => $this->estimatedDays,
            'estimated_delivery' => $this->estimatedDelivery,
        ];
    }
}
