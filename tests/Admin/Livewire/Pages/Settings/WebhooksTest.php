<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Bus;
use Livewire\Livewire;
use Shopper\Core\Enum\WebhookDeliveryStatus;
use Shopper\Core\Jobs\DeliverWebhookJob;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\WebhookDelivery;
use Shopper\Core\Models\WebhookEvent;
use Shopper\Core\Models\WebhookSubscription;
use Shopper\Core\Webhooks\WebhookUrl;
use Shopper\Livewire\Pages\Settings\Webhooks;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('system.settings');
    $this->actingAs($this->user);

    WebhookUrl::resolveHostUsing(fn (): array => ['93.184.216.34']);
});

afterEach(function (): void {
    WebhookUrl::resolveHostUsing(null);
});

describe(Webhooks::class, function (): void {
    it('can render the webhooks settings page', function (): void {
        WebhookSubscription::factory()->create();

        Livewire::test(Webhooks::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.webhooks');
    });

    it('creates a subscription with a generated secret', function (): void {
        Livewire::test(Webhooks::class)
            ->callAction('createWebhook', [
                'url' => 'https://erp.example.com/hooks',
                'events' => ['order.paid'],
            ])
            ->assertHasNoActionErrors();

        $subscription = WebhookSubscription::query()->sole();

        expect($subscription->url)->toBe('https://erp.example.com/hooks')
            ->and(mb_strlen($subscription->secret))->toBeGreaterThanOrEqual(32);
    });

    it('refuses to create a subscription past the configured cap', function (): void {
        config()->set('shopper.webhooks.max_subscriptions', 2);
        WebhookSubscription::factory()->count(2)->create();

        Livewire::test(Webhooks::class)
            ->callAction('createWebhook', [
                'url' => 'https://erp.example.com/hooks',
                'events' => ['order.paid'],
            ]);

        expect(WebhookSubscription::query()->count())->toBe(2);
    });

    it('regenerates the secret and invalidates the previous one', function (): void {
        $subscription = WebhookSubscription::factory()->create(['secret' => 'old-secret']);

        Livewire::test(Webhooks::class)
            ->callTableAction('regenerateSecret', $subscription);

        $newSecret = $subscription->refresh()->secret;

        expect($newSecret)->not->toBe('old-secret')
            ->and(mb_strlen($newSecret))->toBeGreaterThanOrEqual(32);
    });

    it('rejects a webhook url resolving to a private address', function (): void {
        WebhookUrl::resolveHostUsing(fn (): array => ['169.254.169.254']);

        Livewire::test(Webhooks::class)
            ->callAction('createWebhook', [
                'url' => 'https://metadata.example.com/steal',
                'events' => ['order.paid'],
            ])
            ->assertHasActionErrors(['url']);

        expect(WebhookSubscription::query()->count())->toBe(0);
    });

    it('redelivers the last delivery when the resource still exists', function (): void {
        Bus::fake([DeliverWebhookJob::class]);

        $product = Product::factory()->standard()->create();
        $subscription = WebhookSubscription::factory()->create();

        $event = WebhookEvent::query()->create([
            'name' => 'product.updated',
            'resource_type' => $product->getMorphClass(),
            'resource_id' => $product->public_id,
            'payload' => ['name' => $product->name],
        ]);

        WebhookDelivery::query()->create([
            'webhook_event_id' => $event->id,
            'webhook_subscription_id' => $subscription->id,
            'status' => WebhookDeliveryStatus::Failed,
        ]);

        Livewire::test(Webhooks::class)
            ->callTableAction('redeliver', $subscription);

        expect(WebhookEvent::query()->count())->toBe(1);

        $newDelivery = $subscription->deliveries()->latest('id')->first();

        expect($subscription->deliveries()->count())->toBe(2)
            ->and($newDelivery->webhook_event_id)->toBe($event->id);

        Bus::assertDispatched(DeliverWebhookJob::class, function (DeliverWebhookJob $job) use ($newDelivery): bool {
            $deliveryId = (fn (): int => $this->deliveryId)->call($job);

            return $deliveryId === $newDelivery->id;
        });
    });

    it('refuses to redeliver when the resource has been erased', function (): void {
        Bus::fake([DeliverWebhookJob::class]);

        $product = Product::factory()->standard()->create();
        $subscription = WebhookSubscription::factory()->create();

        $event = WebhookEvent::query()->create([
            'name' => 'product.deleted',
            'resource_type' => $product->getMorphClass(),
            'resource_id' => $product->public_id,
            'payload' => ['name' => $product->name],
        ]);

        WebhookDelivery::query()->create([
            'webhook_event_id' => $event->id,
            'webhook_subscription_id' => $subscription->id,
            'status' => WebhookDeliveryStatus::Failed,
        ]);

        $product->forceDelete();

        Livewire::test(Webhooks::class)
            ->callTableAction('redeliver', $subscription);

        expect($subscription->deliveries()->count())->toBe(1);
        Bus::assertNothingDispatched();
    });
})->group('livewire', 'settings', 'webhooks');
