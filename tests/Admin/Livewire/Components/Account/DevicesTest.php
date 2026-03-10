<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Shopper\Livewire\Components\Account\Devices;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);
    $this->actingAs($this->user);
});

describe(Devices::class, function (): void {
    it('can render devices component', function (): void {
        Livewire::test(Devices::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.components.account.devices');
    });

    it('validates incorrect password when logging out other sessions', function (): void {
        Livewire::test(Devices::class)
            ->callAction('logoutOtherBrowsers', [
                'password' => 'wrong-password',
            ])
            ->assertHasFormErrors(['password']);
    });

    it('validates empty password when logging out other sessions', function (): void {
        Livewire::test(Devices::class)
            ->callAction('logoutOtherBrowsers', [
                'password' => '',
            ])
            ->assertHasFormErrors(['password' => 'required']);
    });

    it('can logout other browser sessions with correct password', function (): void {
        Livewire::test(Devices::class)
            ->callAction('logoutOtherBrowsers', [
                'password' => 'password',
            ])
            ->assertHasNoFormErrors();
    });
})->group('livewire', 'account');
