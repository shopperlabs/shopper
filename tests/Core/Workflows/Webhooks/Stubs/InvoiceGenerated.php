<?php

declare(strict_types=1);

namespace Tests\Core\Workflows\Webhooks\Stubs;

final class InvoiceGenerated
{
    public function __construct(
        public readonly string $number,
    ) {}
}
