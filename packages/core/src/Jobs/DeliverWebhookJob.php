<?php

declare(strict_types=1);

namespace Shopper\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Shopper\Core\Enum\WebhookDeliveryStatus;
use Shopper\Core\Models\WebhookDelivery;
use Shopper\Core\Webhooks\WebhookSignature;
use Shopper\Core\Webhooks\WebhookUrl;
use Throwable;

final class DeliverWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        private readonly int $deliveryId,
    ) {}

    /**
     * Hard ceiling on the job so a slow DNS resolution or a stalled TLS
     * handshake cannot pin a worker: the HTTP call is bounded by its own
     * timeout, this bounds everything around it (DNS included, which has no
     * portable per-call timeout of its own).
     */
    public function timeout(): int
    {
        return (int) config('shopper.webhooks.timeout', 10) + 20;
    }

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return (array) config('shopper.webhooks.backoff', [10, 30, 60]);
    }

    public function tries(): int
    {
        return count($this->backoff()) + 1;
    }

    public function handle(): void
    {
        $delivery = WebhookDelivery::query()
            ->with(['event', 'subscription'])
            ->find($this->deliveryId);

        if ($delivery === null || $delivery->status === WebhookDeliveryStatus::Succeeded) {
            return;
        }

        $subscription = $delivery->subscription;

        if (! $subscription->is_active) {
            return;
        }

        $pinnedAddress = WebhookUrl::safeAddressFor($subscription->url);

        if ($pinnedAddress === null) {
            $delivery->update([
                'status' => WebhookDeliveryStatus::Rejected,
                'response_body' => 'The webhook url resolves to a forbidden address.',
                'completed_at' => now(),
            ]);

            return;
        }

        $body = (string) json_encode($this->payloadFor($delivery), JSON_UNESCAPED_SLASHES);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Shopper-Event' => $delivery->event->name,
                'X-Shopper-Event-Id' => (string) $delivery->event->public_id,
                'X-Shopper-Delivery-Id' => (string) $delivery->public_id,
                'X-Shopper-Signature' => WebhookSignature::sign($body, $subscription->secret),
            ])
                ->timeout((int) config('shopper.webhooks.timeout', 10))
                ->withOptions([
                    'allow_redirects' => false,
                    'curl' => WebhookUrl::pinnedResolveOptions($subscription->url, $pinnedAddress),
                ])
                ->withBody($body)
                ->post($subscription->url);
        } catch (ConnectionException) {
            $this->recordFailure($delivery, null, 'Connection failed or timed out.');

            return;
        }

        if ($response->successful()) {
            $delivery->update([
                'status' => WebhookDeliveryStatus::Succeeded,
                'attempt_number' => $this->attempts(),
                'response_code' => $response->status(),
                'completed_at' => now(),
            ]);

            return;
        }

        if ($response->clientError()) {
            $delivery->update([
                'status' => WebhookDeliveryStatus::Rejected,
                'attempt_number' => $this->attempts(),
                'response_code' => $response->status(),
                'response_body' => mb_substr($response->body(), 0, 1000),
                'completed_at' => now(),
            ]);

            return;
        }

        $this->recordFailure($delivery, $response->status(), mb_substr($response->body(), 0, 1000));
    }

    /**
     * Terminal safety net for exceptions the delivery flow does not handle
     * itself (a DB blip during an update, an unexpected client error).
     * Without it the delivery row would stay `pending` forever and the
     * failing endpoint would never count toward auto-disable.
     */
    public function failed(?Throwable $exception): void
    {
        $delivery = WebhookDelivery::query()
            ->with('subscription')
            ->find($this->deliveryId);

        if ($delivery === null || $delivery->status === WebhookDeliveryStatus::Succeeded) {
            return;
        }

        $delivery->update([
            'status' => WebhookDeliveryStatus::Failed,
            'response_body' => mb_substr($exception?->getMessage() ?? 'The delivery job failed.', 0, 1000),
            'completed_at' => now(),
        ]);

        $delivery->subscription->disableWhenFailing();
    }

    /**
     * @return array<string, mixed>
     */
    private function payloadFor(WebhookDelivery $delivery): array
    {
        return [
            'id' => $delivery->event->public_id,
            'event' => $delivery->event->name,
            'created_at' => $delivery->event->created_at->toIso8601String(),
            'attempt' => $this->attempts(),
            'resource' => [
                'type' => $delivery->event->resource_type,
                'id' => $delivery->event->resource_id,
            ],
            'data' => $delivery->event->payload,
        ];
    }

    /**
     * Record a transport-level failure (5xx or timeout) and schedule the
     * next attempt.
     *
     * While attempts remain, releases the job with the backoff delay for
     * the current attempt (`shopper.webhooks.backoff`). On the final
     * attempt, stamps `completed_at` and calls
     * `WebhookSubscription::disableWhenFailing()`. 4xx responses never
     * reach this method: they are terminal `rejected` and are not retried.
     */
    private function recordFailure(WebhookDelivery $delivery, ?int $code, string $body): void
    {
        $exhausted = $this->attempts() >= $this->tries();

        $delivery->update([
            'status' => WebhookDeliveryStatus::Failed,
            'attempt_number' => $this->attempts(),
            'response_code' => $code,
            'response_body' => $body,
            'completed_at' => $exhausted ? now() : null,
        ]);

        if ($exhausted) {
            $delivery->subscription->disableWhenFailing();

            return;
        }

        $this->release($this->backoff()[$this->attempts() - 1] ?? 3600);
    }
}
