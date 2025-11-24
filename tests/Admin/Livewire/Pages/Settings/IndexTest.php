<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Settings\Index;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('can render settings index component', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.index');
    });
})->group('livewire', 'settings');
