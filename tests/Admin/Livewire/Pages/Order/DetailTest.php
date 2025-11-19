<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\User;
use Shopper\Livewire\Pages\Order\Detail;

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
})->group('livewire', 'orders');
