<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Payments;

use Illuminate\Foundation\Events\Dispatchable;

final class PaymentOrphaned
{
    use Dispatchable;

    public bool $recognized = false;

    public bool $resolved = false;

    public bool $deferred = false;

    public function __construct(
        public string $driver,
        public string $reference,
        public ?int $amount = null,
        public ?string $cartId = null,
    ) {}

    public function recognize(): void
    {
        $this->recognized = true;
    }

    public function resolve(): void
    {
        $this->resolved = true;
    }

    public function defer(): void
    {
        $this->deferred = true;
    }
}
