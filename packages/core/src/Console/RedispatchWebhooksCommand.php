<?php

declare(strict_types=1);

namespace Shopper\Core\Console;

use Illuminate\Console\Command;
use Shopper\Core\Enum\WebhookDeliveryStatus;
use Shopper\Core\Jobs\DeliverWebhookJob;
use Shopper\Core\Models\WebhookDelivery;

final class RedispatchWebhooksCommand extends Command
{
    protected $signature = 'shopper:webhooks:redispatch';

    protected $description = 'Requeue webhook deliveries that never reached the queue or were lost by a dead worker';

    /**
     * Closes the at-least-once gap left by infrastructure failures: a
     * delivery is reclaimed when its job was never queued (`dispatch_failed`,
     * queue broker down at push time) or when it sat `pending` past the
     * stale window (worker died after reserving the job). `failed` rows
     * without `completed_at` are NOT reclaimed — those have a live retry
     * scheduled through the job's own backoff.
     */
    public function handle(): int
    {
        $reclaimed = 0;

        WebhookDelivery::query()
            ->where('status', WebhookDeliveryStatus::DispatchFailed)
            ->orWhere(fn ($query) => $query
                ->where('status', WebhookDeliveryStatus::Pending)
                ->where('created_at', '<', now()->subMinutes(15)))
            ->orderBy('id')
            ->each(function (WebhookDelivery $delivery) use (&$reclaimed): void {
                $delivery->update(['status' => WebhookDeliveryStatus::Pending]);

                DeliverWebhookJob::dispatch($delivery->id)
                    ->onQueue((string) config('shopper.webhooks.queue', 'webhooks'));

                $reclaimed++;
            });

        $this->info("Requeued {$reclaimed} webhook deliveries.");

        return self::SUCCESS;
    }
}
