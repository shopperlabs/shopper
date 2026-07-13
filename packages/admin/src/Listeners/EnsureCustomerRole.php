<?php

declare(strict_types=1);

namespace Shopper\Listeners;

use Shopper\Core\Events\Orders\OrderCreated;

final class EnsureCustomerRole
{
    public function handle(OrderCreated $event): void
    {
        $event->order->customer?->assignRole(config('shopper.admin.roles.user'));
    }
}
