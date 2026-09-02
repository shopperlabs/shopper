<?php

declare(strict_types=1);

namespace Shopper\Payment\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Payment\Actions\SyncPaymentWithProvider;

final class SyncPendingPaymentJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $uniqueFor = 3600;

    public function __construct(
        private readonly int $orderId,
        private readonly string $reference,
    ) {}

    public function uniqueId(): string
    {
        return "{$this->orderId}:{$this->reference}";
    }

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return (array) config('shopper.payment.reconciliation.backoff', [60, 300, 900]);
    }

    public function tries(): int
    {
        return count($this->backoff()) + 1;
    }

    public function handle(SyncPaymentWithProvider $sync): void
    {
        $order = resolve(Order::class)::query()->find($this->orderId);

        if ($order === null) {
            return;
        }

        $sync->execute($order, $this->reference);
    }
}
