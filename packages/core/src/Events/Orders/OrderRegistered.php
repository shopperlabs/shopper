<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Orders;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Shopper\Core\Models\Contracts\Order;

final class OrderRegistered implements ShouldQueueAfterCommit
{
    use Dispatchable, InteractsWithQueue, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Order $order
    ) {}
}
