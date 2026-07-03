<?php

declare(strict_types=1);

namespace Shopper\Core\Listeners;

use Illuminate\Support\Facades\Log;
use Shopper\Core\Contracts\WebhookPayloadSerializer;
use Shopper\Core\Enum\WebhookDeliveryStatus;
use Shopper\Core\Jobs\DeliverWebhookJob;
use Shopper\Core\Models\WebhookDelivery;
use Shopper\Core\Models\WebhookEvent;
use Shopper\Core\Models\WebhookSubscription;
use Throwable;

/**
 * Fans a domain event out to the webhook subscriptions listening to it.
 *
 * Runs synchronously in the dispatching request. Do not make this listener
 * queued: domain events use `SerializesModels`, so a queued listener
 * re-fetches the model on wake-up and throws `ModelNotFoundException` for
 * every `*Deleted` event. The payload is therefore snapshotted here, and
 * the queued `DeliverWebhookJob` only posts the frozen JSON.
 *
 * Per event: resolves the public name from `shopper.webhooks.events`,
 * reads the cached active-subscription list (no query on stores without
 * webhooks), creates one `WebhookEvent` row (payload stored once), then one
 * pending `WebhookDelivery` plus one queued job per matching subscription.
 *
 * Every failure path is contained: a failing subscription never aborts the
 * remaining ones, and no exception escapes `handle()` — the listener runs
 * in an after-commit callback of a business transaction (checkout, payment)
 * and must never turn a committed order into a 500.
 */
final readonly class DispatchWebhooksListener
{
    public function __construct(
        private WebhookPayloadSerializer $serializer,
    ) {}

    public function handle(object $event): void
    {
        try {
            $this->dispatchWebhooks($event);
        } catch (Throwable $exception) {
            Log::error('Webhook fan-out failed.', [
                'event' => $event::class,
                'exception' => $exception->getMessage(),
            ]);
        }
    }

    private function dispatchWebhooks(object $event): void
    {
        $name = config('shopper.webhooks.events.'.$event::class);

        if (! is_string($name)) {
            return;
        }

        $subscriptions = WebhookSubscription::active()
            ->filter(fn (WebhookSubscription $subscription): bool => $subscription->listensTo($name));

        if ($subscriptions->isEmpty()) {
            return;
        }

        $serialized = $this->serializer->serialize($event);

        $webhookEvent = WebhookEvent::query()->create([
            'name' => $name,
            'resource_type' => $serialized['resource_type'],
            'resource_id' => $serialized['resource_id'],
            'payload' => $serialized['data'],
        ]);

        foreach ($subscriptions as $subscription) {
            $delivery = null;

            try {
                $delivery = WebhookDelivery::query()->create([
                    'webhook_event_id' => $webhookEvent->id,
                    'webhook_subscription_id' => $subscription->id,
                    'status' => WebhookDeliveryStatus::Pending,
                ]);

                DeliverWebhookJob::dispatch($delivery->id)
                    ->onQueue((string) config('shopper.webhooks.queue', 'webhooks'));
            } catch (Throwable $exception) {
                $delivery?->update(['status' => WebhookDeliveryStatus::DispatchFailed]);

                Log::error('Webhook delivery could not be queued.', [
                    'subscription_id' => $subscription->id,
                    'delivery_id' => $delivery?->id,
                    'exception' => $exception->getMessage(),
                ]);
            }
        }
    }
}
