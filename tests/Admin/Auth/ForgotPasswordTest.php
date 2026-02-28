<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Auth\ForgotPassword;

uses(Tests\TestCase::class);

describe(ForgotPassword::class, function (): void {
    it('can render forgot password component', function (): void {
        Livewire::test(ForgotPassword::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.auth.forgot-password');
    });

    it('validates email is required', function (): void {
        Livewire::test(ForgotPassword::class)
            ->set('data.email', '')
            ->call('sendResetPasswordLink')
            ->assertHasErrors(['data.email' => 'required']);
    });

    it('validates email format', function (): void {
        Livewire::test(ForgotPassword::class)
            ->set('data.email', 'invalid-email')
            ->call('sendResetPasswordLink')
            ->assertHasErrors(['data.email' => 'email']);
    });
})->group('livewire', 'auth');
