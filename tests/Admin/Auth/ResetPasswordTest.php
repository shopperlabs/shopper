<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Auth\ResetPassword;

uses(Tests\TestCase::class);

describe(ResetPassword::class, function (): void {
    it('can render reset password component', function (): void {
        Livewire::test(ResetPassword::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.auth.reset-password');
    });

    it('mounts with token and email from request', function (): void {
        $component = Livewire::withQueryParams(['email' => 'test@example.com'])
            ->test(ResetPassword::class, ['token' => 'test-token']);

        expect($component->get('email'))->toBe('test@example.com')
            ->and($component->get('token'))->toBe('test-token');
    });

    it('validates required fields', function (): void {
        Livewire::test(ResetPassword::class, ['token' => null])
            ->set('email', '')
            ->set('password', '')
            ->call('resetPassword')
            ->assertHasErrors(['token', 'email', 'password']);
    });

    it('validates email format', function (): void {
        Livewire::test(ResetPassword::class, ['token' => 'test-token'])
            ->set('email', 'invalid-email')
            ->set('password', 'Password123!')
            ->call('resetPassword')
            ->assertHasErrors(['email' => 'email']);
    });
})->group('livewire', 'auth');
