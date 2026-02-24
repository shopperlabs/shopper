<?php

declare(strict_types=1);

namespace Shopper\Core\Taxes;

final readonly class TaxLine
{
    public function __construct(
        public int $taxRateId,
        public string $name,
        public ?string $code,
        public float $rate,
        public int $amount,
    ) {}
}
