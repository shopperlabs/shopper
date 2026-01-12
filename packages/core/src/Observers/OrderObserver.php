<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Events\Orders\OrderCreated;
use Shopper\Core\Events\Orders\OrderDeleted;
use Shopper\Core\Models\Order;

final class OrderObserver
{
    public function created(Order $order): void
    {
        event(new OrderCreated($order));
    }

    public function deleting(Order $order): void
    {
        event(new OrderDeleted($order));
    }
}
