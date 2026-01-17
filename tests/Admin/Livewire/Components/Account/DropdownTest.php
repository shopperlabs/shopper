<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Components\Account\Dropdown;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);
    $this->actingAs($this->user);
});

describe(Dropdown::class, function (): void {
    it('can render dropdown component', function (): void {
        Livewire::test(Dropdown::class)
            ->assertOk();
    });

    it('loads authenticated user on mount', function (): void {
        $component = Livewire::test(Dropdown::class);

        expect($component->get('user')->id)->toBe($this->user->id)
            ->and($component->get('user')->first_name)->toBe('John')
            ->and($component->get('user')->last_name)->toBe('Doe');
    });

    it('refreshes user data when updated-profile event is dispatched', function (): void {
        $component = Livewire::test(Dropdown::class);

        $this->user->update(['first_name' => 'Jane']);

        $component->dispatch('profile.updated');

        expect($component->get('user')->first_name)->toBe('Jane');
    });

    it('listens to updated-profile event', function (): void {
        $component = Livewire::test(Dropdown::class);

        $this->user->update(['last_name' => 'Smith']);

        $component->dispatch('profile.updated');

        $updatedUser = $component->get('user');

        expect($updatedUser->last_name)->toBe('Smith');
    });
})->group('livewire', 'components', 'account');
