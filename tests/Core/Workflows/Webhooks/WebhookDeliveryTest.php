<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Shopper\Core\Enum\WebhookDeliveryStatus;
use Shopper\Core\Jobs\DeliverWebhookJob;
use Shopper\Core\Models\WebhookDelivery;
use Shopper\Core\Models\WebhookEvent;
use Shopper\Core\Models\WebhookSubscription;
use Shopper\Core\Webhooks\WebhookSignature;
use Shopper\Core\Webhooks\WebhookUrl;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    WebhookUrl::resolveHostUsing(fn (): array => ['93.184.216.34']);
});

afterEach(function (): void {
    WebhookUrl::resolveHostUsing(null);
});

function makeDelivery(array $subscriptionAttributes = []): WebhookDelivery
{
    $subscription = WebhookSubscription::factory()->create(array_merge([
        'url' => 'https://receiver.test/hooks',
        'secret' => 'shhh-secret',
    ], $subscriptionAttributes));

    $event = WebhookEvent::query()->create([
        'name' => 'order.paid',
        'resource_type' => 'order',
        'resource_id' => '01TESTORDERID',
        'payload' => ['number' => 'ORD-1', 'price_amount' => 5000],
    ]);

    return WebhookDelivery::query()->create([
        'webhook_event_id' => $event->id,
        'webhook_subscription_id' => $subscription->id,
        'status' => WebhookDeliveryStatus::Pending,
    ]);
}

describe('Webhook delivery job', function (): void {
    it('posts the signed payload and marks the delivery succeeded', function (): void {
        Http::fake(['receiver.test/*' => Http::response('', 200)]);

        $delivery = makeDelivery();

        (new DeliverWebhookJob($delivery->id))->handle();

        Http::assertSent(function ($request) use ($delivery): bool {
            $body = $request->body();

            return $request->url() === 'https://receiver.test/hooks'
                && $request->hasHeader('X-Shopper-Event', 'order.paid')
                && $request->hasHeader('X-Shopper-Event-Id', (string) $delivery->event->public_id)
                && WebhookSignature::verify($request->header('X-Shopper-Signature')[0], $body, 'shhh-secret')
                && data_get(json_decode($body, true), 'data.number') === 'ORD-1'
                && data_get(json_decode($body, true), 'id') === $delivery->event->public_id;
        });

        expect($delivery->refresh()->status)->toBe(WebhookDeliveryStatus::Succeeded)
            ->and($delivery->response_code)->toBe(200);
    });

    it('marks a 4xx as rejected without retrying', function (): void {
        Http::fake(['receiver.test/*' => Http::response('bad payload', 422)]);

        $delivery = makeDelivery();

        (new DeliverWebhookJob($delivery->id))->handle();

        expect($delivery->refresh()->status)->toBe(WebhookDeliveryStatus::Rejected)
            ->and($delivery->response_code)->toBe(422)
            ->and($delivery->completed_at)->not->toBeNull();
    });

    it('refuses to deliver to a url resolving to a forbidden address', function (): void {
        Http::fake();

        $delivery = makeDelivery(['url' => 'https://127.0.0.1/steal']);

        (new DeliverWebhookJob($delivery->id))->handle();

        Http::assertNothingSent();
        expect($delivery->refresh()->status)->toBe(WebhookDeliveryStatus::Rejected);
    });

    it('skips delivery for a subscription that was disabled meanwhile', function (): void {
        Http::fake();

        $delivery = makeDelivery(['is_active' => false]);

        (new DeliverWebhookJob($delivery->id))->handle();

        Http::assertNothingSent();
        expect($delivery->refresh()->status)->toBe(WebhookDeliveryStatus::Pending);
    });

    it('disables a subscription whose recent history is nothing but failures', function (): void {
        config()->set('shopper.webhooks.disable_after_failures', 3);

        $subscription = WebhookSubscription::factory()->create();
        $event = WebhookEvent::query()->create(['name' => 'order.paid', 'payload' => []]);

        foreach (range(1, 3) as $i) {
            WebhookDelivery::query()->create([
                'webhook_event_id' => $event->id,
                'webhook_subscription_id' => $subscription->id,
                'status' => WebhookDeliveryStatus::Failed,
            ]);
        }

        $subscription->disableWhenFailing();

        expect($subscription->refresh()->is_active)->toBeFalse();
    });

    it('keeps a subscription active while a recent delivery succeeded', function (): void {
        config()->set('shopper.webhooks.disable_after_failures', 3);

        $subscription = WebhookSubscription::factory()->create();
        $event = WebhookEvent::query()->create(['name' => 'order.paid', 'payload' => []]);

        foreach ([WebhookDeliveryStatus::Failed, WebhookDeliveryStatus::Succeeded, WebhookDeliveryStatus::Failed] as $status) {
            WebhookDelivery::query()->create([
                'webhook_event_id' => $event->id,
                'webhook_subscription_id' => $subscription->id,
                'status' => $status,
            ]);
        }

        $subscription->disableWhenFailing();

        expect($subscription->refresh()->is_active)->toBeTrue();
    });

    it('does not count rejected deliveries toward the failure window', function (): void {
        config()->set('shopper.webhooks.disable_after_failures', 3);

        $subscription = WebhookSubscription::factory()->create();
        $event = WebhookEvent::query()->create(['name' => 'order.paid', 'payload' => []]);

        foreach ([
            WebhookDeliveryStatus::Failed,
            WebhookDeliveryStatus::Rejected,
            WebhookDeliveryStatus::Rejected,
            WebhookDeliveryStatus::Failed,
            WebhookDeliveryStatus::Failed,
        ] as $status) {
            WebhookDelivery::query()->create([
                'webhook_event_id' => $event->id,
                'webhook_subscription_id' => $subscription->id,
                'status' => $status,
            ]);
        }

        $subscription->disableWhenFailing();

        expect($subscription->refresh()->is_active)->toBeFalse();
    });

    it('does not let a success outside the recent window keep the subscription active', function (): void {
        config()->set('shopper.webhooks.disable_after_failures', 3);

        $subscription = WebhookSubscription::factory()->create();
        $event = WebhookEvent::query()->create(['name' => 'order.paid', 'payload' => []]);

        foreach ([
            WebhookDeliveryStatus::Succeeded,
            WebhookDeliveryStatus::Failed,
            WebhookDeliveryStatus::Failed,
            WebhookDeliveryStatus::Failed,
        ] as $status) {
            WebhookDelivery::query()->create([
                'webhook_event_id' => $event->id,
                'webhook_subscription_id' => $subscription->id,
                'status' => $status,
            ]);
        }

        $subscription->disableWhenFailing();

        expect($subscription->refresh()->is_active)->toBeFalse();
    });

    it('does not disable a subscription with fewer deliveries than the threshold', function (): void {
        config()->set('shopper.webhooks.disable_after_failures', 5);

        $subscription = WebhookSubscription::factory()->create();
        $event = WebhookEvent::query()->create(['name' => 'order.paid', 'payload' => []]);

        foreach (range(1, 4) as $i) {
            WebhookDelivery::query()->create([
                'webhook_event_id' => $event->id,
                'webhook_subscription_id' => $subscription->id,
                'status' => WebhookDeliveryStatus::Failed,
            ]);
        }

        $subscription->disableWhenFailing();

        expect($subscription->refresh()->is_active)->toBeTrue();
    });

    it('stores the webhook secret encrypted at rest', function (): void {
        $subscription = WebhookSubscription::factory()->create(['secret' => 'plain-secret-value']);

        $raw = Illuminate\Support\Facades\DB::table(shopper_table('webhook_subscriptions'))
            ->where('id', $subscription->id)
            ->value('secret');

        expect($raw)->not->toBe('plain-secret-value')
            ->and($subscription->secret)->toBe('plain-secret-value');
    });

    it('requeues dispatch_failed and stale pending deliveries, leaving live retries alone', function (): void {
        Illuminate\Support\Facades\Bus::fake([DeliverWebhookJob::class]);

        $delivery = makeDelivery();
        $event = $delivery->event;
        $subscriptionId = $delivery->webhook_subscription_id;

        $delivery->update(['status' => WebhookDeliveryStatus::DispatchFailed]);

        $stalePending = WebhookDelivery::query()->create([
            'webhook_event_id' => $event->id,
            'webhook_subscription_id' => $subscriptionId,
            'status' => WebhookDeliveryStatus::Pending,
        ]);
        $stalePending->updateQuietly(['created_at' => now()->subMinutes(30)]);

        $freshPending = WebhookDelivery::query()->create([
            'webhook_event_id' => $event->id,
            'webhook_subscription_id' => $subscriptionId,
            'status' => WebhookDeliveryStatus::Pending,
        ]);

        $liveRetry = WebhookDelivery::query()->create([
            'webhook_event_id' => $event->id,
            'webhook_subscription_id' => $subscriptionId,
            'status' => WebhookDeliveryStatus::Failed,
        ]);

        $this->artisan('shopper:webhooks:redispatch')->assertSuccessful();

        Illuminate\Support\Facades\Bus::assertDispatchedTimes(DeliverWebhookJob::class, 2);

        expect($delivery->refresh()->status)->toBe(WebhookDeliveryStatus::Pending)
            ->and($stalePending->refresh()->status)->toBe(WebhookDeliveryStatus::Pending)
            ->and($freshPending->refresh()->status)->toBe(WebhookDeliveryStatus::Pending)
            ->and($liveRetry->refresh()->status)->toBe(WebhookDeliveryStatus::Failed);
    });

    it('releases the job with the configured backoff delay on a 5xx response', function (): void {
        config()->set('shopper.webhooks.backoff', [15, 45]);
        Http::fake(['receiver.test/*' => Http::response('down', 503)]);

        $delivery = makeDelivery();
        $job = (new DeliverWebhookJob($delivery->id))->withFakeQueueInteractions();

        $job->handle();

        $job->assertReleased(delay: 15);
        expect($delivery->refresh()->status)->toBe(WebhookDeliveryStatus::Failed)
            ->and($delivery->attempt_number)->toBe(1)
            ->and($delivery->completed_at)->toBeNull();
    });

    it('records a retryable failure when the connection times out', function (): void {
        Http::fake(fn () => throw new Illuminate\Http\Client\ConnectionException('Connection timed out'));

        $delivery = makeDelivery();
        $job = (new DeliverWebhookJob($delivery->id))->withFakeQueueInteractions();

        $job->handle();

        $job->assertReleased();
        expect($delivery->refresh()->status)->toBe(WebhookDeliveryStatus::Failed)
            ->and($delivery->response_code)->toBeNull()
            ->and($delivery->completed_at)->toBeNull();
    });

    it('marks the delivery terminal and disables the subscription once retries are exhausted', function (): void {
        config()->set('shopper.webhooks.backoff', [10]);
        config()->set('shopper.webhooks.disable_after_failures', 1);
        Http::fake(['receiver.test/*' => Http::response('down', 500)]);

        $delivery = makeDelivery();
        $job = (new DeliverWebhookJob($delivery->id))->withFakeQueueInteractions();
        $job->job->attempts = 2;

        $job->handle();

        $job->assertNotReleased();
        expect($delivery->refresh()->status)->toBe(WebhookDeliveryStatus::Failed)
            ->and($delivery->completed_at)->not->toBeNull()
            ->and($delivery->subscription->refresh()->is_active)->toBeFalse();
    });

    it('reaches a terminal state through the failed handler when the job dies on an unexpected exception', function (): void {
        config()->set('shopper.webhooks.disable_after_failures', 1);

        $delivery = makeDelivery();

        (new DeliverWebhookJob($delivery->id))->failed(new RuntimeException('worker died'));

        expect($delivery->refresh()->status)->toBe(WebhookDeliveryStatus::Failed)
            ->and($delivery->completed_at)->not->toBeNull()
            ->and($delivery->response_body)->toBe('worker died')
            ->and($delivery->subscription->refresh()->is_active)->toBeFalse();
    });

    it('prunes old succeeded deliveries but keeps recent failures', function (): void {
        $delivery = makeDelivery();
        $event = $delivery->event;

        $old = WebhookDelivery::query()->create([
            'webhook_event_id' => $event->id,
            'webhook_subscription_id' => $delivery->webhook_subscription_id,
            'status' => WebhookDeliveryStatus::Succeeded,
        ]);
        $old->updateQuietly(['created_at' => now()->subDays(10)]);

        $failed = WebhookDelivery::query()->create([
            'webhook_event_id' => $event->id,
            'webhook_subscription_id' => $delivery->webhook_subscription_id,
            'status' => WebhookDeliveryStatus::Failed,
        ]);
        $failed->updateQuietly(['created_at' => now()->subDays(10)]);

        $this->artisan('model:prune', ['--model' => [WebhookDelivery::class]]);

        expect(WebhookDelivery::query()->whereKey($old->id)->exists())->toBeFalse()
            ->and(WebhookDelivery::query()->whereKey($failed->id)->exists())->toBeTrue();
    });
})->group('core', 'webhooks');

describe('Webhook signature', function (): void {
    it('signs and verifies within the tolerance window', function (): void {
        $signature = WebhookSignature::sign('{"a":1}', 'secret');

        expect(WebhookSignature::verify($signature, '{"a":1}', 'secret'))->toBeTrue()
            ->and(WebhookSignature::verify($signature, '{"a":2}', 'secret'))->toBeFalse()
            ->and(WebhookSignature::verify($signature, '{"a":1}', 'wrong'))->toBeFalse();
    });

    it('rejects a replayed signature outside the tolerance window', function (): void {
        $signature = WebhookSignature::sign('{"a":1}', 'secret', now()->subMinutes(10)->getTimestamp());

        expect(WebhookSignature::verify($signature, '{"a":1}', 'secret'))->toBeFalse();
    });
})->group('core', 'webhooks');
