<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;
use Shopper\Livewire\Pages\Order\AbandonedCarts;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('orders.browse');
    $this->actingAs($this->user);
});

describe(AbandonedCarts::class, function (): void {
    it('lists the customers of abandoned carts by name in the customer filter', function (): void {
        $customer = User::factory()->create();
        $cart = Cart::factory()->create(['customer_id' => $customer->id]);
        CartLine::factory()->create(['cart_id' => $cart->id]);
        $cart->forceFill(['updated_at' => now()->subDay()])->saveQuietly();

        $options = Livewire::test(AbandonedCarts::class)
            ->instance()
            ->getTable()
            ->getFilter('customer_id')
            ->getOptions();

        expect($options)->toBe([$customer->id => $customer->full_name]);
    });
})->group('livewire', 'orders');
