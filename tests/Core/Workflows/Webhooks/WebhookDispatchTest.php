<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Bus;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Core\Events\Products\ProductDeleted;
use Shopper\Core\Jobs\DeliverWebhookJob;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\WebhookDelivery;
use Shopper\Core\Models\WebhookEvent;
use Shopper\Core\Models\WebhookSubscription;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    Bus::fake([DeliverWebhookJob::class]);
});

describe('Webhook dispatch', function (): void {
    it('snapshots the event and queues one delivery per subscribed endpoint', function (): void {
        WebhookSubscription::factory()->create(['events' => ['order.paid']]);
        WebhookSubscription::factory()->create(['events' => ['order.paid', 'order.created']]);
        WebhookSubscription::factory()->create(['events' => ['product.created']]);

        $order = Order::factory()->create(['price_amount' => 5000]);

        event(new OrderPaid($order));

        $webhookEvent = WebhookEvent::query()->where('name', 'order.paid')->sole();

        expect($webhookEvent->payload['number'])->toBe($order->number)
            ->and($webhookEvent->payload['price_amount'])->toBe(5000)
            ->and(WebhookDelivery::query()->where('webhook_event_id', $webhookEvent->id)->count())->toBe(2);
    });

    it('does nothing when no subscription listens to the event', function (): void {
        WebhookSubscription::factory()->create(['events' => ['product.created']]);

        event(new OrderPaid(Order::factory()->create()));

        expect(WebhookEvent::query()->count())->toBe(0);
        Bus::assertNothingDispatched();
    });

    it('ignores inactive subscriptions', function (): void {
        WebhookSubscription::factory()->inactive()->create(['events' => ['order.paid']]);

        event(new OrderPaid(Order::factory()->create()));

        expect(WebhookDelivery::query()->count())->toBe(0);
    });

    it('snapshots a deleted product so the delete webhook can still be delivered', function (): void {
        WebhookSubscription::factory()->create(['events' => ['product.deleted']]);

        $product = Product::factory()->standard()->create(['name' => 'Gone Soon']);
        $productId = $product->public_id;

        event(new ProductDeleted($product));

        $product->forceDelete();

        $webhookEvent = WebhookEvent::query()->sole();

        expect($webhookEvent->payload['name'])->toBe('Gone Soon')
            ->and($webhookEvent->payload['id'])->toBe($productId)
            ->and($webhookEvent->resource_id)->toBe($productId);
    });

    it('marks a delivery dispatch_failed when the queue rejects the push, without blocking the others', function (): void {
        WebhookSubscription::factory()->create(['events' => ['order.paid']]);
        WebhookSubscription::factory()->create(['events' => ['order.paid']]);

        $order = Order::factory()->create();

        $calls = 0;
        Bus::shouldReceive('dispatch')->andReturnUsing(function () use (&$calls): void {
            if (++$calls === 1) {
                throw new RuntimeException('queue unavailable');
            }
        });

        event(new OrderPaid($order));

        $statuses = WebhookDelivery::query()
            ->whereHas('event', fn ($query) => $query->where('name', 'order.paid'))
            ->orderBy('id')
            ->pluck('status');

        expect($statuses->count())->toBe(2)
            ->and($statuses[0])->toBe(Shopper\Core\Enum\WebhookDeliveryStatus::DispatchFailed)
            ->and($statuses[1])->toBe(Shopper\Core\Enum\WebhookDeliveryStatus::Pending);
    });

    it('stores the event payload encrypted at rest', function (): void {
        WebhookSubscription::factory()->create(['events' => ['order.paid']]);

        $order = Order::factory()->create();

        event(new OrderPaid($order));

        $webhookEvent = WebhookEvent::query()->where('name', 'order.paid')->sole();

        $raw = Illuminate\Support\Facades\DB::table(shopper_table('webhook_events'))
            ->where('id', $webhookEvent->id)
            ->value('payload');

        expect($raw)->not->toContain($order->number)
            ->and($webhookEvent->payload['number'])->toBe($order->number);
    });

    it('never ships auth material in a customer payload', function (): void {
        WebhookSubscription::factory()->create(['events' => ['customer.registered']]);

        $customer = Tests\Core\Stubs\User::factory()->create();

        event(new Shopper\Core\Events\Customers\CustomerRegistered($customer));

        $payload = WebhookEvent::query()->sole()->payload;

        expect($payload)->not->toHaveKey('password')
            ->not->toHaveKey('remember_token')
            ->not->toHaveKey('store_two_factor_secret');
    });
})->group('core', 'webhooks');
