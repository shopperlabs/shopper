<?php

declare(strict_types=1);

namespace Shopper\Payment\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Shopper\Core\Models\Contracts\Order as OrderContract;
use Shopper\Core\Models\Order;
use Shopper\Payment\Actions\SettlePayment;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Jobs\SyncPendingPaymentJob;
use Shopper\Payment\Models\PaymentTransaction;
use Shopper\Payment\Models\PaymentWebhookEvent;
use Shopper\Payment\Services\PaymentProcessingService;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'shopper:payments:reconcile')]
final class ReconcilePaymentsCommand extends Command
{
    protected $signature = 'shopper:payments:reconcile
                            {--pull : Queue a provider check for pending payments whose driver supports retrieval}
                            {--minutes=15 : Only pull orders that have been pending for at least this many minutes}';

    protected $description = 'Apply the provider events that arrived before their order existed, and optionally check pending payments against the provider';

    public function handle(PaymentProcessingService $payments, SettlePayment $settle): int
    {
        ['settled' => $settled, 'unmatched' => $unmatched] = $this->replayEarlyEvents($payments, $settle);

        $this->components->info("{$settled} ".Str::plural('payment', $settled).' settled from early provider events.');
        $this->components->info("{$unmatched} ".Str::plural('event', $unmatched).' still without an order.');

        if ($this->option('pull')) {
            $queued = $this->queuePendingPayments((int) $this->option('minutes'));

            $this->components->info("{$queued} pending ".Str::plural('payment', $queued).' queued for a provider check.');
        }

        return self::SUCCESS;
    }

    /**
     * Events are settled per reference so the ordering rules of the
     * settlement apply to the whole history of a payment. The reference list
     * stays short: events find their order within seconds, only those of
     * abandoned payment sessions linger.
     *
     * @return array{settled: int, unmatched: int}
     */
    private function replayEarlyEvents(PaymentProcessingService $payments, SettlePayment $settle): array
    {
        $settled = 0;
        $unmatched = 0;

        $references = PaymentWebhookEvent::query()
            ->unprocessed()
            ->whereNotNull('reference')
            ->distinct()
            ->pluck('reference');

        foreach ($references as $reference) {
            if ($payments->findOrderByReference($reference) === null) {
                $unmatched++;

                continue;
            }

            $settle->execute($reference);
            $settled++;
        }

        return ['settled' => $settled, 'unmatched' => $unmatched];
    }

    private function queuePendingPayments(int $minutes): int
    {
        $queued = 0;
        $queue = config('shopper.payment.reconciliation.queue');

        resolve(OrderContract::class)::query()
            ->awaitingPayment()
            ->where('created_at', '<', now()->subMinutes($minutes))
            ->whereHas('paymentMethod', function (Builder $query): void {
                $query->whereNotNull('driver')->where('driver', '<>', 'manual');
            })
            ->chunkById(100, function (Collection $orders) use (&$queued, $queue): void {
                /** @var Order $order */
                foreach ($orders as $order) {
                    $reference = PaymentTransaction::query()
                        ->where('order_id', $order->getKey())
                        ->where('type', TransactionType::Initiate)
                        ->latest()
                        ->value('reference');

                    if (! is_string($reference)) {
                        continue;
                    }

                    SyncPendingPaymentJob::dispatch($order->getKey(), $reference)->onQueue($queue);
                    $queued++;
                }
            });

        return $queued;
    }
}
