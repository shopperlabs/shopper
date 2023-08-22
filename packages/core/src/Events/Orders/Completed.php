<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Orders;

use Illuminate\Queue\SerializesModels;
use Shopper\Core\Models\Order;

final class Completed
{
    use SerializesModels;

    public function __construct(public Order $order)
    {
    }
}
