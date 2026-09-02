<?php

declare(strict_types=1);

namespace Shopper\Payment\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Payment\Enum\TransactionType;

final class PaymentFailed implements ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Order $order,
        public TransactionType $type,
        public ?string $reason = null,
    ) {}
}
