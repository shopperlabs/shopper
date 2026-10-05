<?php

declare(strict_types=1);

namespace Shopper\Payment\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Shopper\Core\Contracts\PaymentSessionGateway;
use Shopper\Core\Events\Payments\OrphanedPaymentRefunded;
use Shopper\Core\Events\Payments\PaymentOrphaned;
use Shopper\Core\Models\Contracts\Order as OrderContract;
use Shopper\Core\Models\Order;
use Shopper\Payment\Actions\SettlePayment;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Enum\WebhookAction;
use Shopper\Payment\Exceptions\PaymentException;
use Shopper\Payment\Jobs\SyncPendingPaymentJob;
use Shopper\Payment\Models\PaymentTransaction;
use Shopper\Payment\Models\PaymentWebhookEvent;
use Shopper\Payment\PaymentManager;
use Shopper\Payment\Services\PaymentProcessingService;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;

#[AsCommand(name: 'shopper:payments:reconcile')]
final class ReconcilePaymentsCommand extends Command
{
    protected $signature = 'shopper:payments:reconcile
                            {--pull : Queue a provider check for pending payments whose driver supports retrieval}
                            {--minutes=15 : Only pull orders that have been pending for at least this many minutes}';

    protected $description = 'Apply the provider events that arrived before their order existed, and optionally check pending payments against the provider';

    public function handle(PaymentProcessingService $payments, SettlePayment $settle, PaymentSessionGateway $gateway, PaymentManager $paymentManager): int
    {
        ['settled' => $settled, 'unmatched' => $unmatched] = $this->replayEarlyEvents($settle);

        $this->components->info("{$settled} ".Str::plural('payment', $settled).' settled from early provider events.');
        $this->components->info("{$unmatched} ".Str::plural('event', $unmatched).' still without an order.');

        $orphaned = $this->resolveOrphans($payments, $gateway, $paymentManager);

        $this->components->info("{$orphaned} orphaned ".Str::plural('payment', $orphaned).' reported.');

        if ($this->option('pull')) {
            $queued = $this->queuePendingPayments((int) $this->option('minutes'));

            $this->components->info("{$queued} pending ".Str::plural('payment', $queued).' queued for a provider check.');
        }

        return self::SUCCESS;
    }

    /**
     * Events are settled per reference so the ordering rules of the
     * settlement apply to the whole history of a payment.
     *
     * @return array{settled: int, unmatched: int}
     */
    private function replayEarlyEvents(SettlePayment $settle): array
    {
        $pending = PaymentWebhookEvent::query()
            ->unprocessed()
            ->whereNotNull('reference');

        $references = $pending->clone()
            ->whereIn('reference', PaymentTransaction::query()->whereHas('order')->select('reference'))
            ->distinct()
            ->pluck('reference');

        $unmatched = $pending->distinct()->count('reference') - $references->count();

        foreach ($references as $reference) {
            $settle->execute($reference);
        }

        return ['settled' => $references->count(), 'unmatched' => $unmatched];
    }

    private function resolveOrphans(PaymentProcessingService $payments, PaymentSessionGateway $gateway, PaymentManager $paymentManager): int
    {
        $orphaned = 0;

        $candidates = PaymentWebhookEvent::query()
            ->unprocessed()
            ->whereNull('orphaned_at')
            ->whereNotNull('reference')
            ->whereIn('type', [WebhookAction::Authorized->value, WebhookAction::Captured->value])
            ->where('created_at', '<', now()->subMinutes((int) config('shopper.payment.reconciliation.orphan_after_minutes', 30)));

        $returns = PaymentWebhookEvent::query()
            ->whereIn('type', [WebhookAction::Refunded->value, WebhookAction::Canceled->value])
            ->whereIn('reference', $candidates->clone()->select('reference'))
            ->get()
            ->groupBy('reference');

        $seen = [];

        foreach ($candidates->lazyByIdDesc() as $event) {
            /** @var string $reference */
            $reference = $event->reference;

            if (isset($seen[$reference])) {
                continue;
            }

            $seen[$reference] = true;
            $result = $event->toWebhookResult();
            $cartId = is_string($result->data['cart_id'] ?? null) ? $result->data['cart_id'] : null;
            $returned = $returns->get($reference);

            if ($returned !== null) {
                if ($cartId !== null && $returned->doesntContain('type', WebhookAction::Canceled) && PaymentWebhookEvent::refundedAmount($returned) < (int) $result->amount) {
                    report(PaymentException::orphaned($event->driver, $reference));
                    $orphaned++;
                }

                PaymentWebhookEvent::query()->forReference($reference)->update(['orphaned_at' => now()]);

                continue;
            }

            $orphan = new PaymentOrphaned($event->driver, $reference, $result->amount, $cartId);

            try {
                event($orphan);

                if ($orphan->deferred || $payments->findOrderByReference($reference) !== null) {
                    continue;
                }

                if ($orphan->recognized && ! $orphan->resolved) {
                    report(PaymentException::orphaned($event->driver, $reference));

                    if (config('shopper.payment.reconciliation.orphans') === 'refund') {
                        $refunded = $gateway->refund(['driver' => $event->driver, 'reference' => $reference, 'amount' => $result->amount]);

                        if (! $refunded && $paymentManager->driver($event->driver)->supportsRetrieval()) {
                            continue;
                        }

                        if ($refunded) {
                            event(new OrphanedPaymentRefunded($event->driver, $reference));
                        }
                    }

                    $orphaned++;
                }
            } catch (Throwable $exception) {
                report($exception);

                continue;
            }

            PaymentWebhookEvent::query()->forReference($reference)->update(['orphaned_at' => now()]);
        }

        return $orphaned;
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
