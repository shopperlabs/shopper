<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Orders;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Shopper\Core\Models\Order;

class AddNoteToOrder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Order $order
    ) {}
}
