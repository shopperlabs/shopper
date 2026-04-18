<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Components\SessionExpired;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

describe(SessionExpired::class, function (): void {
    it('renders the session expired modal', function (): void {
        Livewire::test(SessionExpired::class)
            ->assertSuccessful()
            ->assertSee('session-expired');
    });

    it('regenerates the session when the password is correct', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $oldSessionId = session()->getId();

        Livewire::test(SessionExpired::class)
            ->set('data.password', 'password')
            ->call('attempt')
            ->assertHasNoErrors()
            ->assertDispatched('close-modal', id: 'session-expired');

        expect(session()->getId())->not->toBe($oldSessionId);
    });

    it('rejects an incorrect password', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(SessionExpired::class)
            ->set('data.password', 'wrong-password')
            ->call('attempt')
            ->assertHasErrors(['data.password']);
    });

    it('rejects when the user is no longer authenticated', function (): void {
        Livewire::test(SessionExpired::class)
            ->set('data.password', 'anything')
            ->call('attempt')
            ->assertHasErrors(['data.password']);
    });

    it('clears the password field after a successful attempt', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(SessionExpired::class)
            ->set('data.password', 'password')
            ->call('attempt')
            ->assertSet('data.password', null);
    });

    it('rate limits after too many failed attempts', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $component = Livewire::test(SessionExpired::class);

        for ($i = 0; $i < 5; $i++) {
            $component
                ->set('data.password', 'wrong')
                ->call('attempt')
                ->assertHasErrors(['data.password']);
        }

        $component
            ->set('data.password', 'wrong')
            ->call('attempt')
            ->assertHasErrors(['data.password']);
    });
});
