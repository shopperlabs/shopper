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

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('read_orders');
    $this->actingAs($this->user);
});

describe(Detail::class, function (): void {
    it('can render order detail component', function (): void {
        $order = Order::factory()->hasItems(1)->create();

        Livewire::test(Detail::class, ['order' => $order])
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.orders.detail');
    });

    it('loads order with relationships on mount', function (): void {
        $order = Order::factory()->hasItems(1)->create();

        $component = Livewire::test(Detail::class, ['order' => $order]);

        expect($component->get('order')->id)->toBe($order->id)
            ->and($component->get('order')->relationLoaded('items'))->toBeTrue();
    });

    it('dispatches Cancel event when cancelling order', function (): void {
        Event::fake();

        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::New,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('cancelOrder');

        Event::assertDispatched(Orders\OrderCancelled::class, fn ($event): bool => $event->order->id === $order->id);
    });

    it('update order status to cancelled', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::New,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('cancelOrder');

        $order->refresh();

        expect($order->status)->toBe(OrderStatus::Cancelled);
    });

    it('starts processing when calling `startProcessing` action', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::New,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('startProcessing');

        $order->refresh();

        expect($order->status)->toBe(OrderStatus::Processing);
    });

    it('dispatches Paid event when marking order as paid', function (): void {
        Event::fake();

        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('markPaid');

        Event::assertDispatched(Orders\OrderPaid::class, fn ($event): bool => $event->order->id === $order->id);
    });

    it('updates `payment_status` to paid and advances lifecycle to processing', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('markPaid');

        $order->refresh();

        expect($order->payment_status)->toBe(PaymentStatus::Paid)
            ->and($order->status)->toBe(OrderStatus::Processing);
    });

    it('keeps processing status when marking as paid on a processing order', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('markPaid');

        $order->refresh();

        expect($order->payment_status)->toBe(PaymentStatus::Paid)
            ->and($order->status)->toBe(OrderStatus::Processing);
    });

    it('dispatches Completed event when marking order as complete', function (): void {
        Event::fake();

        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('markComplete');

        Event::assertDispatched(Orders\OrderCompleted::class, fn ($event): bool => $event->order->id === $order->id);
    });

    it('update order status to completed', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('markComplete');

        $order->refresh();

        expect($order->status)->toBe(OrderStatus::Completed);
    });

    it('can archive order via action', function (): void {
        Event::fake();

        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('archive');

        Event::assertDispatched(Orders\OrderArchived::class, fn ($event): bool => $event->order->id === $order->id);
    });

    it('update order status to archived after archiving', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('archive');

        $order->refresh();

        expect($order->status)->toBe(OrderStatus::Archived);
    });

    it('archive action is hidden for completed orders', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Completed,
            'payment_status' => PaymentStatus::Paid,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->assertActionHidden('archive');
    });

    it('archive action is hidden for paid orders', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Processing,
            'payment_status' => PaymentStatus::Paid,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->assertActionHidden('archive');
    });

    it('archive action is visible for new unpaid orders', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->assertActionVisible('archive');
    });

    it('`startProcessing` action is hidden for non-new orders', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Processing,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->assertActionHidden('startProcessing');
    });

    it('`markComplete` action is hidden when not processing or not paid', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->assertActionHidden('markComplete');
    });
})->group('livewire', 'orders');
