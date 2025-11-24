<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Account;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(Account::class, function (): void {
    it('can render account component', function (): void {
        Livewire::test(Account::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.account');
    });
});
