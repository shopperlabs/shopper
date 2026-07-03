<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Bus;
use Shopper\Cart\Events\CartCompleted;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Jobs\DeliverWebhookJob;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\WebhookEvent;
use Shopper\Core\Models\WebhookSubscription;

uses(Tests\Cart\TestCase::class);

it('dispatches `cart.completed` with the resulting order as the resource', function (): void {
    Bus::fake([DeliverWebhookJob::class]);

    WebhookSubscription::factory()->create(['events' => ['cart.completed']]);

    $order = Order::factory()->create(['price_amount' => 12000]);
    $cart = Cart::factory()->create();

    event(new CartCompleted($cart, $order));

    $webhookEvent = WebhookEvent::query()->where('name', 'cart.completed')->sole();

    expect($webhookEvent->payload['number'])->toBe($order->number)
        ->and($webhookEvent->resource_type)->toBe($order->getMorphClass());
})->group('cart', 'webhooks');
