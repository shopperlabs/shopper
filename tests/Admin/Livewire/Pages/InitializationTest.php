<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Initialization;

uses(Tests\Admin\TestCase::class);

describe(Initialization::class, function (): void {
    it('can render initialization component for an admin', function (): void {
        $this->asAdmin();

        Livewire::test(Initialization::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.initialization');
    });

    it('forbids a non-admin user from rendering the page', function (): void {
        $this->actingAs(
            \Tests\Core\Stubs\User::factory()->create(),
            config('shopper.auth.guard'),
        );

        Livewire::test(Initialization::class)->assertStatus(403);
    });
})->group('livewire', 'pages');
