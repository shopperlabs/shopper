<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\OrderShipping;
use Shopper\Livewire\SlideOvers\ShipmentAddEvent;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

describe(ShipmentAddEvent::class, function (): void {
    it('blocks opening the form for users without `orders.edit`', function (): void {
        $user = User::factory()->create();
        $user->givePermissionTo('orders.browse', 'orders.read');

        $this->actingAs($user);

        Livewire::test(ShipmentAddEvent::class, ['shipment' => OrderShipping::factory()->create()])
            ->assertForbidden();
    });
})->group('livewire', 'orders', 'security');
