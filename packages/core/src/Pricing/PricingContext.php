<?php

declare(strict_types=1);

namespace Shopper\Core\Pricing;

final readonly class PricingContext
{
    public function __construct(
        public ?string $currencyCode = null,
        public ?int $customerId = null,
        public int $quantity = 1,
        public ?int $channelId = null,
        public ?int $zoneId = null,
    ) {}

    public function currency(): string
    {
        return $this->currencyCode ?? shopper_currency();
    }
}
