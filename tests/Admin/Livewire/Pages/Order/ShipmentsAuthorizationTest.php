<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\OrderShipping;
use Shopper\Livewire\Pages\Order\Shipments;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('orders.browse');
    $this->actingAs($this->user);
});

describe('Shipments authorization', function (): void {
    it('hides `markDelivered` and `edit` table actions for users without `orders.edit`', function (): void {
        $shipment = OrderShipping::factory()->create([
            'status' => ShipmentStatus::OutForDelivery,
        ]);

        Livewire::test(Shipments::class)
            ->assertActionHidden(TestAction::make('markDelivered')->table($shipment))
            ->assertActionHidden(TestAction::make('edit')->table($shipment));
    });
})->group('livewire', 'orders', 'security');
