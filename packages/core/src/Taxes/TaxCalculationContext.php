<?php

declare(strict_types=1);

namespace Shopper\Core\Taxes;

use Shopper\Core\Models\Contracts\TaxZone;

final readonly class TaxCalculationContext
{
    public function __construct(
        public string $countryCode,
        public ?string $provinceCode = null,
        public ?int $customerId = null,
        public ?TaxZone $resolvedZone = null,
    ) {}

    public function cacheKey(): string
    {
        return $this->countryCode.':'.($this->provinceCode ?? '');
    }
}
