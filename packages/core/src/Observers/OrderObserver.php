<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Events\Orders\Created;
use Shopper\Core\Events\Orders\Deleted;
use Shopper\Core\Models\Order;

final class OrderObserver
{
    public function created(Order $order): void
    {
        event(new Created($order));
    }

    public function deleting(Order $order): void
    {
        event(new Deleted($order));
    }
}
