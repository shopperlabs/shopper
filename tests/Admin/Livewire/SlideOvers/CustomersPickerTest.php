<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\SlideOvers\CustomersPicker;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('customers.browse', 'discounts.edit');
    $this->actingAs($this->user);
});

describe(CustomersPicker::class, function (): void {
    it('lists customers for users with `customers.browse`', function (): void {
        $customer = User::factory()->create();

        Livewire::test(CustomersPicker::class, ['ability' => 'discounts.edit'])
            ->assertSee($customer->email);
    });

    it('requires `customers.browse` whatever ability the caller passes', function (): void {
        $this->user->revokePermissionTo('customers.browse');

        Livewire::test(CustomersPicker::class)->assertForbidden();
        Livewire::test(CustomersPicker::class, ['ability' => 'discounts.edit'])->assertForbidden();
    });
})->group('livewire', 'slide-overs', 'customers');
