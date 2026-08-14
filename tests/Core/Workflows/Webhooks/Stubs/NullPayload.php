<?php

declare(strict_types=1);

namespace Tests\Core\Workflows\Webhooks\Stubs;

use Shopper\Core\Models\Order;

final class NullPayload
{
    public function __construct(
        public readonly Order $order,
    ) {}
}
