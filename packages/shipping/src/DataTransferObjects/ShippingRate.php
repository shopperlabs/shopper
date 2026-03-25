<?php

declare(strict_types=1);

namespace Shopper\Shipping\DataTransferObjects;

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
        return shopper_money_format($this->amount, $this->currency);
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
