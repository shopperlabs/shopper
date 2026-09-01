<?php

declare(strict_types=1);

namespace Shopper\Core\Listeners\Orders;

use Shopper\Core\Actions\CompleteOrderIfFulfilledAction;
use Shopper\Core\Events\Orders\OrderPaid;

final class CompleteFulfilledOrderListener
{
    public function handle(OrderPaid $event): void
    {
        (new CompleteOrderIfFulfilledAction)->execute($event->order);
    }
}
