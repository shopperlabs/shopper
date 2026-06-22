<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Shopper\Core\Events\Orders\OrderCreated;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Core\Models\Order;
use Shopper\Listeners\Notifications\SendNewOrderNotification;
use Shopper\Listeners\Notifications\SendOrderPaidNotification;
use Shopper\Listeners\Notifications\SendPaymentFailedNotification;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Events\PaymentFailed;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    Event::fake([OrderCreated::class, OrderPaid::class, PaymentFailed::class]);

    $this->admin = User::factory()->create();
    $this->admin->assignRole(config('shopper.admin.roles.admin'));

    $this->manager = User::factory()->create();
    $this->manager->assignRole(config('shopper.admin.roles.manager'));

    $this->customer = User::factory()->create();
    $this->customer->assignRole(config('shopper.admin.roles.user'));
});

it('stores a database notification for every administrator when an order is created', function (): void {
    $order = Order::factory()->create();

    (new SendNewOrderNotification)->handle(new OrderCreated($order));

    expect($this->admin->fresh()->notifications)->toHaveCount(1)
        ->and($this->manager->fresh()->notifications)->toHaveCount(1)
        ->and($this->customer->fresh()->notifications)->toHaveCount(0);

    expect($this->admin->fresh()->notifications->first()->data)
        ->toHaveKey('format', 'filament');
});

it('stores a database notification when an order is paid', function (): void {
    $order = Order::factory()->create();

    (new SendOrderPaidNotification)->handle(new OrderPaid($order));

    expect($this->admin->fresh()->notifications)->toHaveCount(1)
        ->and($this->manager->fresh()->notifications)->toHaveCount(1)
        ->and($this->customer->fresh()->notifications)->toHaveCount(0);
});

it('stores a database notification when a payment fails', function (): void {
    $order = Order::factory()->create();

    (new SendPaymentFailedNotification)->handle(
        new PaymentFailed($order, TransactionType::Capture, 'card_declined')
    );

    expect($this->admin->fresh()->notifications)->toHaveCount(1)
        ->and($this->manager->fresh()->notifications)->toHaveCount(1)
        ->and($this->customer->fresh()->notifications)->toHaveCount(0);
});

it('uses the refund copy when a refund fails', function (): void {
    $order = Order::factory()->create();

    (new SendPaymentFailedNotification)->handle(
        new PaymentFailed($order, TransactionType::Refund, 'insufficient_funds')
    );

    expect($this->admin->fresh()->notifications->first()->data)
        ->toHaveKey('title', __('shopper::notifications.database.refund_failed.title'));
});

it('does not send notifications when there is no administrator', function (): void {
    $this->admin->removeRole(config('shopper.admin.roles.admin'));
    $this->manager->removeRole(config('shopper.admin.roles.manager'));

    $order = Order::factory()->create();

    (new SendNewOrderNotification)->handle(new OrderCreated($order));

    expect($this->customer->fresh()->notifications)->toHaveCount(0);
});
