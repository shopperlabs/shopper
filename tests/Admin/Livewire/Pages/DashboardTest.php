<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Dashboard;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(Dashboard::class, function (): void {
    it('can render dashboard component', function (): void {
        Livewire::test(Dashboard::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.dashboard');
    });

    it('has correct page title', function (): void {
        Livewire::test(Dashboard::class)
            ->assertOk()
            ->assertSet('showSetupGuide', true);
    });
});
