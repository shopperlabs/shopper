<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Supplier;
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
        $user->givePermissionTo('add_suppliers');
        $this->actingAs($user);

        Livewire::test(SupplierForm::class)
            ->assertSuccessful();
    });

    it('requires edit_suppliers to open an existing supplier', function (): void {
        $user = User::factory()->create();
        $user->givePermissionTo('add_suppliers');
        $this->actingAs($user);

        Livewire::test(SupplierForm::class, ['supplier' => Supplier::factory()->create()])
            ->assertForbidden();
    });
})->group('livewire', 'slideovers', 'security');
