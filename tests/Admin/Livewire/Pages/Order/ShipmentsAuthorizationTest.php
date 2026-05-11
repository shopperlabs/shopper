<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\OrderShipping;
use Shopper\Livewire\Pages\Order\Shipments;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('browse_orders');
    $this->actingAs($this->user);
});

describe('Shipments authorization', function (): void {
    it('hides `markDelivered` and `edit` table actions for users without `edit_orders`', function (): void {
        $shipment = OrderShipping::factory()->create([
            'status' => ShipmentStatus::OutForDelivery,
        ]);

        Livewire::test(Shipments::class)
            ->assertTableActionHidden('markDelivered', $shipment)
            ->assertTableActionHidden('edit', $shipment);
    });
})->group('livewire', 'orders', 'security');
