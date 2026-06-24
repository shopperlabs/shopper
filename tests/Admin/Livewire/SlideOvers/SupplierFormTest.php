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
})->group('livewire', 'slideovers', 'security');
