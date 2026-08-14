<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Shopper\Core\Jobs\DeliverWebhookJob;
use Shopper\Core\Models\WebhookEvent;
use Shopper\Core\Models\WebhookSubscription;
use Shopper\Core\Webhooks\Facades\Webhooks;
use Tests\Core\Workflows\Webhooks\Stubs\InvoiceGenerated;
use Tests\Core\Workflows\Webhooks\Stubs\MalformedPayload;
use Tests\Core\Workflows\Webhooks\Stubs\NullPayload;
use Tests\Core\Workflows\Webhooks\Stubs\SubscriptionRenewed;

uses(Tests\Core\Workflows\Webhooks\WebhookRegistryTestCase::class);

beforeEach(function (): void {
    Bus::fake([DeliverWebhookJob::class]);
});

describe('Webhook registry', function (): void {
    it('exposes core and registered events together', function (): void {
        expect(Webhooks::events())
            ->toHaveKey(Shopper\Core\Events\Orders\OrderPaid::class, 'order.paid')
            ->toHaveKey(SubscriptionRenewed::class, 'subscription.renewed')
            ->toHaveKey(InvoiceGenerated::class, 'invoice.generated');
    });

    it('dispatches a registered event through its closure serializer', function (): void {
        WebhookSubscription::factory()->create(['events' => ['subscription.renewed']]);

        event(new SubscriptionRenewed('premium'));

        $webhookEvent = WebhookEvent::query()->where('name', 'subscription.renewed')->sole();

        expect($webhookEvent->resource_type)->toBe('subscription')
            ->and($webhookEvent->resource_id)->toBe('sub_premium')
            ->and($webhookEvent->payload)->toBe(['plan' => 'premium']);

        Bus::assertDispatched(DeliverWebhookJob::class);
    });

    it('dispatches a registered event through its class serializer', function (): void {
        WebhookSubscription::factory()->create(['events' => ['invoice.generated']]);

        event(new InvoiceGenerated('INV-001'));

        $webhookEvent = WebhookEvent::query()->where('name', 'invoice.generated')->sole();

        expect($webhookEvent->resource_type)->toBe('invoice')
            ->and($webhookEvent->payload)->toBe(['number' => 'INV-001']);
    });

    it('falls back to the bound serializer for events registered without one', function (): void {
        WebhookSubscription::factory()->create(['events' => ['order.paid']]);

        $order = Shopper\Core\Models\Order::factory()->create(['price_amount' => 4200]);

        event(new Shopper\Core\Events\Orders\OrderPaid($order));

        $webhookEvent = WebhookEvent::query()->where('name', 'order.paid')->sole();

        expect($webhookEvent->payload['price_amount'])->toBe(4200);
    });

    it('dispatches a webhook for an event registered after the application booted', function (): void {
        $event = new class
        {
            public string $reference = 'ref-1';
        };

        Webhooks::register($event::class, 'post.boot.event', fn (object $e): array => [
            'resource_type' => 'reference',
            'resource_id' => $e->reference,
            'data' => ['reference' => $e->reference],
        ]);

        WebhookSubscription::factory()->create(['events' => ['post.boot.event']]);

        event($event);

        $webhookEvent = WebhookEvent::query()->where('name', 'post.boot.event')->sole();

        expect($webhookEvent->resource_id)->toBe('ref-1')
            ->and($webhookEvent->payload)->toBe(['reference' => 'ref-1']);
    });

    it('refuses to rename an event another registration already owns', function (): void {
        expect(fn () => Webhooks::register(SubscriptionRenewed::class, 'subscription.renamed'))
            ->toThrow(LogicException::class, 'already registered as [subscription.renewed]');
    });

    it('refuses to replace the serializer of an event another registration owns', function (): void {
        expect(fn () => Webhooks::register(SubscriptionRenewed::class, 'subscription.renewed', fn (object $e): array => [
            'resource_type' => null,
            'resource_id' => null,
            'data' => [],
        ]))->toThrow(LogicException::class, 'serializer cannot be replaced');
    });

    it('lets code override a config default and attach a serializer to a core event', function (): void {
        Webhooks::register(Shopper\Core\Events\Orders\OrderPaid::class, 'order.paid', fn (object $e): array => [
            'resource_type' => 'order',
            'resource_id' => 'custom',
            'data' => ['shaped_by' => 'addon'],
        ]);

        WebhookSubscription::factory()->create(['events' => ['order.paid']]);

        event(new Shopper\Core\Events\Orders\OrderPaid(Shopper\Core\Models\Order::factory()->create()));

        $webhookEvent = WebhookEvent::query()->where('name', 'order.paid')->sole();

        expect($webhookEvent->payload)->toBe(['shaped_by' => 'addon'])
            ->and($webhookEvent->resource_id)->toBe('custom');
    });

    it('keeps the store booting when two registrations claim the same public name', function (): void {
        $event = new class {};

        Webhooks::register($event::class, 'order.paid');

        expect(Webhooks::nameFor($event::class))->toBeNull()
            ->and(Webhooks::nameFor(Shopper\Core\Events\Orders\OrderPaid::class))->toBe('order.paid');
    });

    it('accepts re-registering the same event with the same name without doubling deliveries', function (): void {
        Webhooks::register(Shopper\Core\Events\Orders\OrderPaid::class, 'order.paid');

        WebhookSubscription::factory()->create(['events' => ['order.paid']]);

        event(new Shopper\Core\Events\Orders\OrderPaid(Shopper\Core\Models\Order::factory()->create()));

        expect(WebhookEvent::query()->where('name', 'order.paid')->count())->toBe(1);
    });

    it('ignores malformed config entries instead of failing the boot', function (): void {
        Log::spy();

        config()->set('shopper.webhooks.events', [
            Shopper\Core\Events\Orders\OrderPaid::class => Shopper\Core\Enum\WebhookEventType::OrderPaid,
            'events.without.class-string.key',
        ]);

        $registry = new Shopper\Core\Webhooks\WebhookRegistry(app('events'));

        expect($registry->events())->toBe([]);

        Log::shouldHaveReceived('warning')->twice();
    });

    it('still delivers a degraded envelope when a serializer omits the resource keys', function (): void {
        WebhookSubscription::factory()->create(['events' => ['malformed.payload']]);

        event(new MalformedPayload('missing keys'));

        $webhookEvent = WebhookEvent::query()->where('name', 'malformed.payload')->sole();

        expect($webhookEvent->resource_type)->toBeNull()
            ->and($webhookEvent->payload)->toBe(['reason' => 'missing keys']);

        Bus::assertDispatched(DeliverWebhookJob::class);
    });

    it('never falls back to the default payload when a registered serializer returns null', function (): void {
        WebhookSubscription::factory()->create(['events' => ['null.payload']]);

        event(new NullPayload(Shopper\Core\Models\Order::factory()->create(['price_amount' => 9900])));

        $webhookEvent = WebhookEvent::query()->where('name', 'null.payload')->sole();

        expect($webhookEvent->payload)->toBe([])
            ->and($webhookEvent->resource_type)->toBeNull();
    });

    it('still records the event when a rebound payload serializer omits the resource keys', function (): void {
        app()->bind(Shopper\Core\Contracts\WebhookPayloadSerializer::class, fn (): object => new class implements Shopper\Core\Contracts\WebhookPayloadSerializer
        {
            public function serialize(object $event): array
            {
                return ['data' => ['rebound' => true]];
            }
        });

        WebhookSubscription::factory()->create(['events' => ['order.paid']]);

        event(new Shopper\Core\Events\Orders\OrderPaid(Shopper\Core\Models\Order::factory()->create()));

        $webhookEvent = WebhookEvent::query()->where('name', 'order.paid')->sole();

        expect($webhookEvent->payload)->toBe(['rebound' => true])
            ->and($webhookEvent->resource_type)->toBeNull();

        Bus::assertDispatched(DeliverWebhookJob::class);
    });

    it('warns only once per malformed config entry across repeated seeds', function (): void {
        Log::spy();

        config()->set('shopper.webhooks.events', [
            Shopper\Core\Events\Orders\OrderPaid::class => Shopper\Core\Enum\WebhookEventType::OrderPaid,
        ]);

        $registry = new Shopper\Core\Webhooks\WebhookRegistry(app('events'));

        $registry->events();
        $registry->activate();

        Log::shouldHaveReceived('warning')->once();
    });
})->group('webhooks');
