<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\User;
use Shopper\Facades\Shopper;
use Shopper\Livewire\Pages\Auth\Login;
use Shopper\Tests\TestCase;

uses(TestCase::class);

describe(Login::class, function (): void {
    it('can render login page', function (): void {
        $this->get(Shopper::prefix() . '/login')
            ->assertSuccessful();
    });

    it('can authenticate', function (): void {
        $this->assertGuest();

        $userToAuthenticate = User::factory()->create();
        $userToAuthenticate->assignRole(config('shopper.core.users.admin_role'));

        Livewire::test(Login::class)
            ->set('email', $userToAuthenticate->email)
            ->set('password', 'password')
            ->call('authenticate')
            ->assertRedirect(Shopper::prefix() . '/dashboard');

        $this->assertAuthenticatedAs($userToAuthenticate, config('shopper.auth.guard'));
    });
})->group('authenticate');
