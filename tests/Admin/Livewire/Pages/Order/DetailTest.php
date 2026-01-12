<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\OrderStatus;
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

    it('initializes notes property', function (): void {
        $order = Order::factory()->hasItems(1)->create(['notes' => 'Test notes']);

        $component = Livewire::test(Detail::class, ['order' => $order]);

        expect($component->get('notes'))->toBeNull();
    });

    it('passes items to view', function (): void {
        $order = Order::factory()->hasItems(2)->create();

        $component = Livewire::test(Detail::class, ['order' => $order]);
        $items = $component->viewData('items');

        expect($items)->not->toBeNull();
    });

    it('dispatches AddNote event when leaving notes', function (): void {
        Event::fake();

        $order = Order::factory()->hasItems(1)->create();

        Livewire::test(Detail::class, ['order' => $order])
            ->set('notes', 'This is a test note')
            ->call('leaveNotes');

        Event::assertDispatched(Orders\AddNoteToOrder::class, fn ($event): bool => $event->order->id === $order->id);
    });

    it('updates order notes when leaving notes', function (): void {
        $order = Order::factory()->hasItems(1)->create(['notes' => null]);

        Livewire::test(Detail::class, ['order' => $order])
            ->set('notes', 'New note content')
            ->call('leaveNotes');

        $order->refresh();

        expect($order->notes)->toBe('New note content');
    });

    it('dispatches Cancel event when cancelling order', function (): void {
        Event::fake();

        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Completed,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('cancelOrder');

        Event::assertDispatched(Orders\OrderCancel::class, fn ($event): bool => $event->order->id === $order->id);
    });

    it('updates order status to cancelled', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Completed,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('cancelOrder');

        $order->refresh();

        expect($order->status)->toBe(OrderStatus::Cancelled);
    });

    it('dispatches Registered event when registering order', function (): void {
        Event::fake();

        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('register');

        Event::assertDispatched(Orders\OrderRegistered::class, fn ($event): bool => $event->order->id === $order->id);
    });

    it('updates order status to register', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('register');

        $order->refresh();

        expect($order->status)->toBe(OrderStatus::Register);
    });

    it('dispatches Paid event when marking order as paid', function (): void {
        Event::fake();

        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('markPaid');

        Event::assertDispatched(Orders\OrderPaid::class, fn ($event): bool => $event->order->id === $order->id);
    });

    it('updates order status to paid', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Pending,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('markPaid');

        $order->refresh();

        expect($order->status)->toBe(OrderStatus::Paid);
    });

    it('dispatches Completed event when marking order as complete', function (): void {
        Event::fake();

        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Paid,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('markComplete');

        Event::assertDispatched(Orders\OrderCompleted::class, fn ($event): bool => $event->order->id === $order->id);
    });

    it('updates order status to completed', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::Paid,
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->callAction('markComplete');

        $order->refresh();

        expect($order->status)->toBe(OrderStatus::Completed);
    });
})->group('livewire', 'orders');
