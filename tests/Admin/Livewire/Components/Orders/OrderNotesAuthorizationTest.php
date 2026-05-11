<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Events\Orders\OrderNoteAdded;
use Shopper\Core\Models\Order;
use Shopper\Livewire\Components\Orders\OrderNotes;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('read_orders');
    $this->actingAs($this->user);
});

describe('OrderNotes authorization', function (): void {
    it('blocks `leaveNotes` for users without `edit_orders`', function (): void {
        Event::fake();

        $order = Order::factory()->hasItems(1)->create(['notes' => 'original']);

        Livewire::test(OrderNotes::class, ['order' => $order])
            ->set('notes', 'tampered')
            ->call('leaveNotes');

        expect($order->fresh()->notes)->toBe('original');
        Event::assertNotDispatched(OrderNoteAdded::class);
    });

    it('allows `leaveNotes` when user has `edit_orders`', function (): void {
        $this->user->givePermissionTo('edit_orders');

        $order = Order::factory()->hasItems(1)->create(['notes' => 'original']);

        Livewire::test(OrderNotes::class, ['order' => $order])
            ->set('notes', 'updated note')
            ->call('leaveNotes');

        expect($order->fresh()->notes)->toBe('updated note');
    });
})->group('livewire', 'orders', 'security');
