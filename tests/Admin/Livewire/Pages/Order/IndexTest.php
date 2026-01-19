<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Order;
use Shopper\Livewire\Pages\Order\Index;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('browse_orders');
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('can render orders index component', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.orders.index');
    });

    it('can list orders in table', function (): void {
        $orders = Order::factory()
            ->count(3)
            ->hasItems(1)
            ->create();

        Livewire::test(Index::class)
            ->loadTable()
            ->assertCanSeeTableRecords($orders);
    });
})->group('livewire', 'orders');
