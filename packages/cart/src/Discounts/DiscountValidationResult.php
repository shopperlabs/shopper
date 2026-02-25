<?php

declare(strict_types=1);

namespace Shopper\Cart\Discounts;

final readonly class DiscountValidationResult
{
    public function __construct(
        public bool $valid,
        public ?string $failureReason = null,
    ) {}
}
