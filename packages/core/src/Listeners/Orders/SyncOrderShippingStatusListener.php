<?php

declare(strict_types=1);

namespace Shopper\Core\Listeners\Orders;

use Illuminate\Contracts\Queue\ShouldQueue;
use Shopper\Core\Actions\SyncOrderShippingStatusAction;
use Shopper\Core\Events\Orders\OrderShipmentCreated;

final class SyncOrderShippingStatusListener implements ShouldQueue
{
    public function handle(OrderShipmentCreated $event): void
    {
        (new SyncOrderShippingStatusAction)->execute($event->order);
    }
}
