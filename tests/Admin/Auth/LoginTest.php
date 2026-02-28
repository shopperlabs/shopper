<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Facades\Shopper;
use Shopper\Livewire\Pages\Auth\Login;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

describe(Login::class, function (): void {
    it('can render login page', function (): void {
        $this->get(Shopper::prefix().'/login')
            ->assertSuccessful();
    });

    it('can authenticate', function (): void {
        $this->assertGuest();

        $userToAuthenticate = User::factory()->create();
        $userToAuthenticate->assignRole(config('shopper.core.roles.admin'));

        Livewire::test(Login::class)
            ->set('data.email', $userToAuthenticate->email)
            ->set('data.password', 'password')
            ->call('authenticate')
            ->assertRedirect(Shopper::prefix().'/dashboard');

        $this->assertAuthenticatedAs($userToAuthenticate, config('shopper.auth.guard'));
    });

    it('can authenticate with remember me', function (): void {
        $user = User::factory()->create();
        $user->assignRole(config('shopper.core.roles.admin'));

        Livewire::test(Login::class)
            ->set('data.email', $user->email)
            ->set('data.password', 'password')
            ->set('data.remember', true)
            ->call('authenticate')
            ->assertRedirect(Shopper::prefix().'/dashboard');

        $this->assertAuthenticatedAs($user, config('shopper.auth.guard'));
    });

    it('fails authentication with invalid credentials', function (): void {
        $user = User::factory()->create();

        Livewire::test(Login::class)
            ->set('data.email', $user->email)
            ->set('data.password', 'wrong-password')
            ->call('authenticate')
            ->assertHasErrors(['data.email']);

        $this->assertGuest(config('shopper.auth.guard'));
    });

    it('fails authentication with non-existent user', function (): void {
        Livewire::test(Login::class)
            ->set('data.email', 'nonexistent@example.com')
            ->set('data.password', 'password')
            ->call('authenticate')
            ->assertHasErrors(['data.email']);

        $this->assertGuest(config('shopper.auth.guard'));
    });

    it('validates required email field', function (): void {
        Livewire::test(Login::class)
            ->set('data.email', '')
            ->set('data.password', 'password')
            ->call('authenticate')
            ->assertHasErrors(['data.email' => 'required']);
    });

    it('validates email format', function (): void {
        Livewire::test(Login::class)
            ->set('data.email', 'invalid-email')
            ->set('data.password', 'password')
            ->call('authenticate')
            ->assertHasErrors(['data.email' => 'email']);
    });

    it('validates required password field', function (): void {
        Livewire::test(Login::class)
            ->set('data.email', 'test@example.com')
            ->set('data.password', '')
            ->call('authenticate')
            ->assertHasErrors(['data.password' => 'required']);
    });
})->group('authenticate');
