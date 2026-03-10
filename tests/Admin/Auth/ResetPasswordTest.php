<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Auth\ResetPassword;

uses(Tests\Admin\TestCase::class);

describe(ResetPassword::class, function (): void {
    it('can render reset password component', function (): void {
        Livewire::test(ResetPassword::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.auth.reset-password');
    });

    it('mounts with token and email from request', function (): void {
        $component = Livewire::withQueryParams(['email' => 'test@example.com'])
            ->test(ResetPassword::class, ['token' => 'test-token']);

        expect($component->get('data.email'))->toBe('test@example.com')
            ->and($component->get('token'))->toBe('test-token');
    });

    it('validates required fields', function (): void {
        Livewire::test(ResetPassword::class, ['token' => null])
            ->set('data.email', '')
            ->set('data.password', '')
            ->call('resetPassword')
            ->assertHasErrors(['data.email', 'data.password']);
    });

    it('validates email format', function (): void {
        Livewire::test(ResetPassword::class, ['token' => 'test-token'])
            ->set('data.email', 'invalid-email')
            ->set('data.password', 'Password123!')
            ->call('resetPassword')
            ->assertHasErrors(['data.email' => 'email']);
    });
})->group('livewire', 'auth');
