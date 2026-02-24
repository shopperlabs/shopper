<?php

declare(strict_types=1);

namespace Shopper\Core\Taxes;

final readonly class TaxCalculationContext
{
    public function __construct(
        public string $countryCode,
        public ?string $provinceCode = null,
        public ?int $customerId = null,
    ) {}
}
