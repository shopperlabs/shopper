<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Payments;

use Illuminate\Foundation\Events\Dispatchable;

final class OrphanedPaymentRefunded
{
    use Dispatchable;

    public function __construct(
        public string $driver,
        public string $reference,
        public ?int $amount = null,
    ) {}
}
