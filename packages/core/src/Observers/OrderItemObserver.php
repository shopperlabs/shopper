<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Events\Orders\OrderItemCreated;
use Shopper\Core\Models\OrderItem;

class OrderItemObserver
{
    public function created(OrderItem $orderItem): void
    {
        event(new OrderItemCreated($orderItem));
    }
}
