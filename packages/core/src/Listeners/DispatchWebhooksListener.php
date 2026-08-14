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
use Shopper\Core\Webhooks\WebhookRegistry;
use Throwable;

final readonly class DispatchWebhooksListener
{
    public function __construct(
        private WebhookRegistry $registry,
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
        $name = $this->registry->nameFor($event::class);

        if ($name === null) {
            return;
        }

        $subscriptions = WebhookSubscription::active()
            ->filter(fn (WebhookSubscription $subscription): bool => $subscription->listensTo($name));

        if ($subscriptions->isEmpty()) {
            return;
        }

        $serialized = $this->registry->serialize($event) ?? $this->normalize($this->serializer->serialize($event));

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

    /**
     * A rebound serializer may not honour the contract's declared shape at
     * runtime, so the payload is normalised before it is persisted.
     *
     * @param  array<string, mixed>  $payload
     * @return array{resource_type: ?string, resource_id: ?string, data: array<string, mixed>}
     */
    private function normalize(array $payload): array
    {
        return [
            'resource_type' => $payload['resource_type'] ?? null,
            'resource_id' => $payload['resource_id'] ?? null,
            'data' => $payload['data'] ?? [],
        ];
    }
}
