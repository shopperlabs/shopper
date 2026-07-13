<?php

declare(strict_types=1);

use Shopper\Core\Models\Order;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

it('assigns the customer role when a user places an order', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole(config('shopper.admin.roles.admin'));

    Order::factory()->create(['customer_id' => $admin->id]);

    $admin->refresh();

    expect($admin->hasRole(config('shopper.admin.roles.user')))->toBeTrue()
        ->and($admin->hasRole(config('shopper.admin.roles.admin')))->toBeTrue()
        ->and(User::customers()->whereKey($admin->id)->exists())->toBeTrue();
});

it('does not duplicate the customer role on repeat orders', function (): void {
    $customer = User::factory()->create();
    $customer->assignRole(config('shopper.admin.roles.user'));

    Order::factory()->count(2)->create(['customer_id' => $customer->id]);

    expect($customer->refresh()->roles)->toHaveCount(1);
});

it('ignores guest orders', function (): void {
    $order = Order::factory()->create(['customer_id' => null]);

    expect($order->exists)->toBeTrue();
});
