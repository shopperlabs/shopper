<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Shopper\Core\Models\User;
use Shopper\Livewire\Modals\ConfirmPassword;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);
    $this->actingAs($this->user);
});

describe(ConfirmPassword::class, function (): void {
    it('can render confirm password modal', function (): void {
        Livewire::test(ConfirmPassword::class, ['action' => 'test-action'])
            ->assertOk();
    });

    it('initializes with correct action', function (): void {
        $component = Livewire::test(ConfirmPassword::class, ['action' => 'test-action']);

        expect($component->get('action'))->toBe('test-action');
    });

    it('initializes with empty password', function (): void {
        $component = Livewire::test(ConfirmPassword::class, ['action' => 'test-action']);

        expect($component->get('confirmablePassword'))->toBe('');
    });

    it('can confirm password with correct password', function (): void {
        Livewire::test(ConfirmPassword::class, ['action' => 'test-action'])
            ->set('confirmablePassword', 'password')
            ->call('confirmPassword')
            ->assertHasNoErrors();

        expect(session('auth.password_confirmed_at'))->not->toBeNull();
    });

    it('dispatches action after successful password confirmation', function (): void {
        Livewire::test(ConfirmPassword::class, ['action' => 'test-action'])
            ->set('confirmablePassword', 'password')
            ->call('confirmPassword')
            ->assertDispatched('test-action');
    });

    it('validates incorrect password', function (): void {
        Livewire::test(ConfirmPassword::class, ['action' => 'test-action'])
            ->set('confirmablePassword', 'wrong-password')
            ->call('confirmPassword')
            ->assertHasErrors(['confirmable_password']);
    });

    it('can stop confirming password', function (): void {
        $component = Livewire::test(ConfirmPassword::class, ['action' => 'test-action'])
            ->set('confirmablePassword', 'some-password')
            ->call('stopConfirmingPassword');

        expect($component->get('confirmablePassword'))->toBe('');
    });

    it('resets password field after successful confirmation', function (): void {
        $component = Livewire::test(ConfirmPassword::class, ['action' => 'test-action'])
            ->set('confirmablePassword', 'password')
            ->call('confirmPassword');

        expect($component->get('confirmablePassword'))->toBe('');
    });

    it('stores password confirmation timestamp in session', function (): void {
        $beforeTime = time();

        Livewire::test(ConfirmPassword::class, ['action' => 'test-action'])
            ->set('confirmablePassword', 'password')
            ->call('confirmPassword');

        $afterTime = time();
        $confirmedAt = session('auth.password_confirmed_at');

        expect($confirmedAt)->toBeGreaterThanOrEqual($beforeTime)
            ->and($confirmedAt)->toBeLessThanOrEqual($afterTime);
    });
})->group('livewire', 'modals', 'auth');
