<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\Country;
use Shopper\Livewire\Components\Customers\Addresses;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('customers.read');
    $this->actingAs($this->user);

    $this->customer = User::factory()->create();
    $this->country = Country::query()->where('cca2', 'CM')->firstOrFail();
});

describe(Addresses::class, function (): void {
    it('can render the addresses component', function (): void {
        Livewire::test(Addresses::class, ['customer' => $this->customer])
            ->assertOk()
            ->assertViewIs('shopper::livewire.components.customers.addresses');
    });

    it('returns empty collections when customer has no addresses', function (): void {
        $component = Livewire::test(Addresses::class, ['customer' => $this->customer])->instance();

        expect($component->shippingAddresses)->toBeEmpty()
            ->and($component->billingAddresses)->toBeEmpty();
    });

    it('separates shipping and billing addresses into distinct collections', function (): void {
        Address::factory()->create([
            'user_id' => $this->customer->id,
            'country_id' => $this->country->id,
            'type' => AddressType::Shipping,
            'shipping_default' => false,
        ]);
        Address::factory()->create([
            'user_id' => $this->customer->id,
            'country_id' => $this->country->id,
            'type' => AddressType::Billing,
            'billing_default' => false,
        ]);

        $component = Livewire::test(Addresses::class, ['customer' => $this->customer])->instance();

        expect($component->shippingAddresses)->toHaveCount(1)
            ->and($component->billingAddresses)->toHaveCount(1)
            ->and($component->shippingAddresses->first()->type)->toBe(AddressType::Shipping)
            ->and($component->billingAddresses->first()->type)->toBe(AddressType::Billing);
    });

    it('sorts shipping addresses with the default one first', function (): void {
        $nonDefault = Address::factory()->create([
            'user_id' => $this->customer->id,
            'country_id' => $this->country->id,
            'type' => AddressType::Shipping,
            'shipping_default' => false,
        ]);
        $default = Address::factory()->create([
            'user_id' => $this->customer->id,
            'country_id' => $this->country->id,
            'type' => AddressType::Shipping,
            'shipping_default' => true,
        ]);

        $addresses = Livewire::test(Addresses::class, ['customer' => $this->customer])
            ->instance()->shippingAddresses;

        expect($addresses->first()->id)->toBe($default->id)
            ->and($addresses->last()->id)->toBe($nonDefault->id);
    });

    it('sorts billing addresses with the default one first', function (): void {
        $nonDefault = Address::factory()->create([
            'user_id' => $this->customer->id,
            'country_id' => $this->country->id,
            'type' => AddressType::Billing,
            'billing_default' => false,
        ]);
        $default = Address::factory()->create([
            'user_id' => $this->customer->id,
            'country_id' => $this->country->id,
            'type' => AddressType::Billing,
            'billing_default' => true,
        ]);

        $addresses = Livewire::test(Addresses::class, ['customer' => $this->customer])
            ->instance()->billingAddresses;

        expect($addresses->first()->id)->toBe($default->id)
            ->and($addresses->last()->id)->toBe($nonDefault->id);
    });

    it('does not mix shipping addresses into the billing collection', function (): void {
        Address::factory()->count(3)->create([
            'user_id' => $this->customer->id,
            'country_id' => $this->country->id,
            'type' => AddressType::Shipping,
            'shipping_default' => false,
        ]);

        $billing = Livewire::test(Addresses::class, ['customer' => $this->customer])
            ->instance()->billingAddresses;

        expect($billing)->toBeEmpty();
    });
})->group('livewire', 'customers');
