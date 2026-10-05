<?php

declare(strict_types=1);

use Illuminate\Console\Scheduling\Event as ScheduledEvent;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Exceptions;
use Shopper\Core\Contracts\PaymentSessionGateway;
use Shopper\Core\Contracts\StockReserver;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Exceptions\PaymentProviderUnavailableException;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Product;
use Tests\Cart\Stubs\FakePaymentSessionGateway;

uses(Tests\Core\TestCase::class);

function pendingOrder(array $attributes = [], string $driver = 'stripe'): Order
{
    return Order::factory()->create([
        'status' => OrderStatus::New,
        'payment_status' => PaymentStatus::Pending,
        'shipping_status' => ShippingStatus::Unfulfilled,
        'payment_method_id' => PaymentMethod::factory()->create(['driver' => $driver])->id,
        ...$attributes,
    ]);
}

describe('shopper:orders:reclaim', function (): void {
    it('cancels unpaid pending orders past the window and releases their stock', function (): void {
        $inventory = Inventory::factory()->create(['is_default' => true, 'priority' => 0]);
        $product = Product::factory()->standard()->create();
        $product->mutateStock($inventory->id, 10, event: 'Initial');

        $order = pendingOrder(['created_at' => now()->subHours(100)]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_type' => $product->getMorphClass(),
            'product_id' => $product->id,
            'quantity' => 4,
        ]);

        resolve(StockReserver::class)->reserve($product, 4, $order);
        expect($product->getStock())->toBe(6);

        $this->artisan('shopper:orders:reclaim', ['--hours' => 72])->assertSuccessful();

        expect($order->refresh()->status)->toBe(OrderStatus::Cancelled)
            ->and($product->getStock())->toBe(10);
    });

    it('leaves recent pending orders untouched', function (): void {
        $order = pendingOrder(['created_at' => now()->subHours(10)]);

        $this->artisan('shopper:orders:reclaim', ['--hours' => 72])->assertSuccessful();

        expect($order->refresh()->status)->toBe(OrderStatus::New);
    });

    it('leaves paid and processing orders untouched regardless of age', function (): void {
        $paid = pendingOrder([
            'payment_status' => PaymentStatus::Paid,
            'created_at' => now()->subHours(100),
        ]);
        $processing = pendingOrder([
            'status' => OrderStatus::Processing,
            'created_at' => now()->subHours(100),
        ]);

        $this->artisan('shopper:orders:reclaim', ['--hours' => 72])->assertSuccessful();

        expect($paid->refresh()->status)->toBe(OrderStatus::New)
            ->and($processing->refresh()->status)->toBe(OrderStatus::Processing);
    });

    it('never reclaims an offline payment order regardless of age', function (): void {
        $cashOnDelivery = pendingOrder(['created_at' => now()->subDays(30)], driver: 'manual');
        $noDriver = pendingOrder(['created_at' => now()->subDays(30)], driver: 'stripe');
        $noDriver->paymentMethod->update(['driver' => null]);

        $this->artisan('shopper:orders:reclaim', ['--hours' => 72])->assertSuccessful();

        expect($cashOnDelivery->refresh()->status)->toBe(OrderStatus::New)
            ->and($noDriver->refresh()->status)->toBe(OrderStatus::New);
    });

    it('never reclaims an order whose payment the provider collected or is still processing', function (): void {
        $gateway = new FakePaymentSessionGateway;
        $gateway->paid = true;
        $this->app->instance(PaymentSessionGateway::class, $gateway);

        $order = pendingOrder(['created_at' => now()->subHours(100)]);

        $this->artisan('shopper:orders:reclaim', ['--hours' => 72])->assertSuccessful();

        expect($order->refresh()->status)->toBe(OrderStatus::New);
    });

    it('keeps an order for the next run when the provider cannot be reached, and reclaims the others', function (): void {
        Exceptions::fake();

        $unreachable = pendingOrder(['created_at' => now()->subHours(100)]);
        $reclaimable = pendingOrder(['created_at' => now()->subHours(100)]);
        $gateway = new FakePaymentSessionGateway;
        $gateway->unavailableOrders = [$unreachable->id];
        $this->app->instance(PaymentSessionGateway::class, $gateway);

        $this->artisan('shopper:orders:reclaim', ['--hours' => 72])->assertSuccessful();

        expect($unreachable->refresh()->status)->toBe(OrderStatus::New)
            ->and($reclaimable->refresh()->status)->toBe(OrderStatus::Cancelled);
        Exceptions::assertReported(PaymentProviderUnavailableException::class);
    });

    it('does nothing when the reclaim window is not configured', function (): void {
        config()->set('shopper.orders.reclaim_pending_after_hours', null);

        $order = pendingOrder(['created_at' => now()->subHours(1000)]);

        $this->artisan('shopper:orders:reclaim')->assertSuccessful();

        expect($order->refresh()->status)->toBe(OrderStatus::New);
    });

    it('uses the configured window when no option is passed', function (): void {
        config()->set('shopper.orders.reclaim_pending_after_hours', 72);

        $order = pendingOrder(['created_at' => now()->subHours(100)]);

        $this->artisan('shopper:orders:reclaim')->assertSuccessful();

        expect($order->refresh()->status)->toBe(OrderStatus::Cancelled);
    });
})->group('workflows', 'orders');

it('schedules the reclaim in the foreground with an overlap lock that expires within the hour', function (): void {
    $event = collect(resolve(Schedule::class)->events())
        ->first(fn (ScheduledEvent $event): bool => str_contains((string) $event->command, 'shopper:orders:reclaim'));

    expect($event)
        ->withoutOverlapping->toBeTrue()
        ->expiresAt->toBe(60)
        ->runInBackground->toBeFalse();
});
