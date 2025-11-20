<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Shopper\Core\Models\User;
use Shopper\Livewire\Modals\LogoutOthersBrowser;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);
    $this->actingAs($this->user);
});

describe(LogoutOthersBrowser::class, function (): void {
    it('can render logout others browser modal', function (): void {
        Livewire::test(LogoutOthersBrowser::class)
            ->assertOk();
    });

    it('initializes with empty password', function (): void {
        $component = Livewire::test(LogoutOthersBrowser::class);

        expect($component->get('password'))->toBe('');
    });

    it('validates incorrect password when empty', function (): void {
        Livewire::test(LogoutOthersBrowser::class)
            ->set('password', '')
            ->call('logoutOtherBrowserSessions', auth()->guard())
            ->assertHasErrors(['password']);
    });

    it('validates incorrect password', function (): void {
        Livewire::test(LogoutOthersBrowser::class)
            ->set('password', 'wrong-password')
            ->call('logoutOtherBrowserSessions', auth()->guard())
            ->assertHasErrors(['password']);
    });

    it('can logout other browser sessions with correct password', function (): void {
        Livewire::test(LogoutOthersBrowser::class)
            ->set('password', 'password')
            ->call('logoutOtherBrowserSessions', auth()->guard())
            ->assertHasNoErrors();
    });

    it('dispatches loggedOut event after logout', function (): void {
        Livewire::test(LogoutOthersBrowser::class)
            ->set('password', 'password')
            ->call('logoutOtherBrowserSessions', auth()->guard())
            ->assertDispatched('loggedOut');
    });
})->group('livewire', 'modals', 'auth');
