<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;
use Shopper\Livewire\Pages\Customers\Show;
use Shopper\Models\Role;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('read_customers');
    $this->actingAs($this->user);

    $this->customerRole = Role::query()->firstOrCreate(['name' => config('shopper.admin.roles.user')]);
});

describe(Show::class, function (): void {
    it('can render customer show component', function (): void {
        $customer = User::factory()->create();
        $customer->assignRole($this->customerRole);

        Livewire::test(Show::class, ['user' => $customer->id])
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.customers.show');
    });

    it('loads customer with relationships on mount', function (): void {
        $customer = User::factory()->create();
        $customer->assignRole($this->customerRole);

        $component = Livewire::test(Show::class, ['user' => $customer->id]);

        expect($component->get('customer'))->not->toBeNull()
            ->and($component->get('customer')->id)->toBe($customer->id);
    });

    it('does not expose a staff account through the customer detail route', function (): void {
        $staff = User::factory()->create();
        $staff->assignRole(Role::query()->firstOrCreate(['name' => config('shopper.admin.roles.admin')]));

        expect(fn (): mixed => Livewire::test(Show::class, ['user' => $staff->id]))
            ->toThrow(ModelNotFoundException::class);
    });
})->group('livewire', 'customers');
