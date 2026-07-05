<?php

declare(strict_types=1);

namespace Shopper\Core\Pricing;

final readonly class ResolvedPrice
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public function __construct(
        public int $amount,
        public string $currencyCode,
        public ?int $compareAmount = null,
        public ?int $originalAmount = null,
        public array $meta = [],
    ) {}
}
