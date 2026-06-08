<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Initialization;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

describe(Initialization::class, function (): void {
    it('can render initialization component', function (): void {
        $this->asAdmin();

        Livewire::test(Initialization::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.initialization');
    });

    it('forbids a non-admin from rendering the initialization component', function (): void {
        $this->actingAs(User::factory()->create());

        Livewire::test(Initialization::class)
            ->assertForbidden();
    });
})->group('livewire', 'pages');
