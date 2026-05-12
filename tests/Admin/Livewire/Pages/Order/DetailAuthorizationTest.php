<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Events\Orders;
use Shopper\Core\Models\Order;
use Shopper\Livewire\Pages\Order\Detail;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('orders.read');
    $this->actingAs($this->user);
});

describe('Detail authorization', function (): void {
    it('hides mutating actions for users without `orders.edit`', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::New,
            'shipping_status' => ShippingStatus::Unfulfilled,
            'payment_status' => PaymentStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->assertActionHidden('cancelOrder')
            ->assertActionHidden('startProcessing')
            ->assertActionHidden('markPaid')
            ->assertActionHidden('archive');
    });

    it('hides `markComplete` for users without `orders.edit`', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->assertActionHidden('markComplete');
    });

    it('hides `capturePayment` for users without `orders.edit`', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Authorized,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->assertActionHidden('capturePayment');

        expect($order->fresh()->payment_status)->toBe(PaymentStatus::Authorized);
    });

    it('allows mutating actions when user has `orders.edit`', function (): void {
        Event::fake();
        $this->user->givePermissionTo('orders.edit');

        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('markPaid');

        expect($order->fresh()->payment_status)->toBe(PaymentStatus::Paid);
        Event::assertDispatched(Orders\OrderPaid::class);
    });
})->group('livewire', 'orders', 'security');
