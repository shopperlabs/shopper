<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Order;
use Shopper\Livewire\Modals\ArchiveOrder;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->order = Order::factory()->create();
});

describe(ArchiveOrder::class, function (): void {
    it('can render archive order modal', function (): void {
        Livewire::test(ArchiveOrder::class, ['order' => $this->order])
            ->assertOk();
    });

    it('initializes with correct order', function (): void {
        $component = Livewire::test(ArchiveOrder::class, ['order' => $this->order]);

        expect($component->get('order')->id)->toBe($this->order->id);
    });

    it('can archive order by soft deleting it', function (): void {
        Livewire::test(ArchiveOrder::class, ['order' => $this->order])
            ->call('archived');

        expect($this->order->fresh()->trashed())->toBeTrue();
    });

    it('redirects to orders index after archiving', function (): void {
        Livewire::test(ArchiveOrder::class, ['order' => $this->order])
            ->call('archived')
            ->assertRedirect(route('shopper.orders.index'));
    });

    it('flashes success message after archiving', function (): void {
        Livewire::test(ArchiveOrder::class, ['order' => $this->order])
            ->call('archived');

        expect(session('success'))->toBe(__('shopper::notifications.orders.archived'));
    });
})->group('livewire', 'modals', 'orders');
