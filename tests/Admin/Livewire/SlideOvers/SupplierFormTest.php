<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\SlideOvers\SupplierForm;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

describe(SupplierForm::class, function (): void {
    it('blocks opening the form for users without supplier permissions', function (): void {
        $this->actingAs(User::factory()->create());

        Livewire::test(SupplierForm::class)
            ->assertForbidden();
    });

    it('opens the form for users with supplier permissions', function (): void {
        $user = User::factory()->create();
        $user->givePermissionTo('suppliers.create');
        $this->actingAs($user);

        Livewire::test(SupplierForm::class)
            ->assertSuccessful();
    });
})->group('livewire', 'slideovers', 'security');
