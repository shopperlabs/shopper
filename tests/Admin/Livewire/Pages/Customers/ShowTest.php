<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Pages\Customers\Show;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('read_customers');
    $this->actingAs($this->user);
});

describe(Show::class, function (): void {
    it('can render customer show component', function (): void {
        $customer = User::factory()->create();

        Livewire::test(Show::class, ['user' => $customer->id])
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.customers.show');
    });

    it('loads customer with relationships on mount', function (): void {
        $customer = User::factory()->create();

        $component = Livewire::test(Show::class, ['user' => $customer->id]);

        expect($component->get('customer'))->not->toBeNull()
            ->and($component->get('customer')->id)->toBe($customer->id);
    });
})->group('livewire', 'customers');
