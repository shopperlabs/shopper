<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\OrderShipping;
use Shopper\Livewire\Pages\Order\Shipments;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('orders.browse');
    $this->actingAs($this->user);
});

describe(Shipments::class, function (): void {
    it('defaults the active tab to `all`', function (): void {
        Livewire::test(Shipments::class)
            ->assertSet('activeTab', 'all');
    });

    it('hydrates the active tab from the URL query string', function (): void {
        Livewire::withQueryParams(['tab' => 'pending'])
            ->test(Shipments::class)
            ->assertSet('activeTab', 'pending');
    });

    it('finds a shipment by the reference it displays', function (): void {
        $shipments = OrderShipping::factory()->count(2)->create();

        Livewire::test(Shipments::class)
            ->searchTable('SHP-'.$shipments[0]->id)
            ->assertCanSeeTableRecords([$shipments[0]])
            ->assertCanNotSeeTableRecords([$shipments[1]]);
    });
})->group('livewire', 'orders');
