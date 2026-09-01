<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Orders;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\OrderShipping;
use Shopper\Core\Models\OrderShippingEvent;

final class OrderShipmentEventRecorded implements ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Order $order,
        public OrderShipping $shipment,
        public OrderShippingEvent $event,
        public bool $statusChanged,
    ) {}
}
