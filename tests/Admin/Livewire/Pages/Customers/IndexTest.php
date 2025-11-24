<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Customers\Index;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('browse_customers');
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('can render customers index component', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.customers.index');
    });

    it('can list customers in table', function (): void {
        User::factory()->count(3)->create();

        Livewire::test(Index::class)
            ->assertOk();
    });
})->group('livewire', 'customers');
