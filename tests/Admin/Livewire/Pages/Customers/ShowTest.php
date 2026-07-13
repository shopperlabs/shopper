<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Order;
use Shopper\Livewire\Pages\Customers\Show;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->assignRole(config('shopper.admin.roles.admin'));
    $this->user->givePermissionTo('customers.read');
    $this->actingAs($this->user);
});

function makeCustomer(array $attributes = []): User
{
    /** @var User $customer */
    $customer = User::factory()->create($attributes);
    $customer->assignRole(config('shopper.admin.roles.user'));

    return $customer;
}

describe(Show::class, function (): void {
    it('can render customer show component', function (): void {
        $customer = makeCustomer();

        Livewire::test(Show::class, ['user' => $customer->id])
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.customers.show');
    });

    it('requires customers.read permission', function (): void {
        $unprivileged = User::factory()->create();
        $this->actingAs($unprivileged);
        $customer = makeCustomer();

        Livewire::test(Show::class, ['user' => $customer->id])
            ->assertForbidden();
    });

    it('aborts when target user is an administrator', function (): void {
        $admin = User::factory()->create();
        $admin->assignRole(config('shopper.admin.roles.admin'));

        Livewire::test(Show::class, ['user' => $admin->id]);
    })->throws(ModelNotFoundException::class);

    it('loads customer with relationships on mount', function (): void {
        $customer = makeCustomer();

        $component = Livewire::test(Show::class, ['user' => $customer->id]);

        expect($component->get('customer'))->not->toBeNull()
            ->and($component->get('customer')->id)->toBe($customer->id);
    });

    it('returns empty stats when customer has no orders', function (): void {
        $customer = makeCustomer();

        $stats = Livewire::test(Show::class, ['user' => $customer->id])->instance()->stats;

        expect($stats)
            ->toMatchArray([
                'ltv' => 0,
                'orders_count' => 0,
                'aov' => 0,
                'last_order_at' => null,
            ]);
    });

    it('computes stats from paid orders only', function (): void {
        $customer = makeCustomer();

        Order::factory()->count(2)->sequence(
            ['price_amount' => 100_00],
            ['price_amount' => 200_00],
        )->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::Completed->value,
            'payment_status' => PaymentStatus::Paid->value,
        ]);

        Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::Completed->value,
            'payment_status' => PaymentStatus::Pending->value,
            'price_amount' => 999_00,
        ]);

        $stats = Livewire::test(Show::class, ['user' => $customer->id])->instance()->stats;

        expect($stats['ltv'])->toBe(300_00)
            ->and($stats['orders_count'])->toBe(2)
            ->and($stats['aov'])->toBe(150_00)
            ->and($stats['last_order_at'])->not->toBeNull();
    });

    it('counts paid orders regardless of currency for active state', function (): void {
        $customer = makeCustomer();

        Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::Completed->value,
            'payment_status' => PaymentStatus::Paid->value,
            'price_amount' => 500_00,
            'currency_code' => 'XOF',
        ]);

        $stats = Livewire::test(Show::class, ['user' => $customer->id])->instance()->stats;

        expect($stats['orders_count'])->toBe(1)
            ->and($stats['ltv'])->toBe(0);
    });

    it('exposes the default shipping address sorted by shipping_default flag', function (): void {
        $customer = makeCustomer();
        $country = Country::query()->where('cca2', 'CM')->firstOrFail();

        Address::query()->create([
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'street_address' => '1 First St',
            'postal_code' => '75001',
            'city' => 'Paris',
            'country_id' => $country->id,
            'user_id' => $customer->id,
            'type' => AddressType::Shipping,
            'shipping_default' => false,
        ]);

        Address::query()->create([
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'street_address' => '99 Default Ave',
            'postal_code' => '69001',
            'city' => 'Lyon',
            'country_id' => $country->id,
            'user_id' => $customer->id,
            'type' => AddressType::Shipping,
            'shipping_default' => true,
        ]);

        $address = Livewire::test(Show::class, ['user' => $customer->id])
            ->instance()->defaultAddress;

        expect($address)->not->toBeNull()
            ->and($address->city)->toBe('Lyon');
    });

    it('falls back to billing address when no shipping address exists', function (): void {
        $customer = makeCustomer();
        $country = Country::query()->where('cca2', 'CM')->firstOrFail();

        Address::query()->create([
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'street_address' => '10 Billing St',
            'postal_code' => '75002',
            'city' => 'Lyon',
            'country_id' => $country->id,
            'user_id' => $customer->id,
            'type' => AddressType::Billing,
        ]);

        $address = Livewire::test(Show::class, ['user' => $customer->id])
            ->instance()->defaultAddress;

        expect($address)->not->toBeNull()
            ->and($address->city)->toBe('Lyon');
    });

    it('returns null default address when customer has none', function (): void {
        $customer = makeCustomer();

        $address = Livewire::test(Show::class, ['user' => $customer->id])
            ->instance()->defaultAddress;

        expect($address)->toBeNull();
    });

    it('exposes previous and next customers limited to the customer role', function (): void {
        $first = makeCustomer();
        $current = makeCustomer();
        $last = makeCustomer();

        $component = Livewire::test(Show::class, ['user' => $current->id]);

        expect($component->instance()->prevCustomer?->id)->toBe($first->id)
            ->and($component->instance()->nextCustomer?->id)->toBe($last->id);
    });

    it('returns null prevCustomer for the first customer in the list', function (): void {
        $only = makeCustomer();

        $component = Livewire::test(Show::class, ['user' => $only->id]);

        expect($component->instance()->prevCustomer)->toBeNull()
            ->and($component->instance()->nextCustomer)->toBeNull();
    });

    it('navigates to another customer via goToCustomer', function (): void {
        $other = makeCustomer();
        $current = makeCustomer();

        Livewire::test(Show::class, ['user' => $current->id])
            ->call('goToCustomer', $other->id)
            ->assertRedirect(route('shopper.customers.show', ['user' => $other->id]));
    });

    it('aborts goToCustomer when target id is an administrator', function (): void {
        $admin = User::factory()->create();
        $admin->assignRole(config('shopper.admin.roles.admin'));
        $current = makeCustomer();

        Livewire::test(Show::class, ['user' => $current->id])
            ->call('goToCustomer', $admin->id)
            ->assertNoRedirect();
    });

    it('reports active state via stats array', function (): void {
        $customer = makeCustomer();

        Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::Completed->value,
            'payment_status' => PaymentStatus::Paid->value,
            'price_amount' => 50_00,
        ]);

        $component = Livewire::test(Show::class, ['user' => $customer->id]);

        expect($component->instance()->stats['orders_count'])->toBeGreaterThan(0);
    });

    it('anonymizes customer data inside a transaction', function (): void {
        $customer = makeCustomer([
            'first_name' => 'Original',
            'last_name' => 'User',
            'email' => 'original@example.com',
            'phone_number' => '0123456789',
            'email_verified_at' => Carbon::parse('2024-01-01 00:00:00'),
        ]);
        $this->user->givePermissionTo('customers.delete');

        $country = Country::query()->where('cca2', 'CM')->firstOrFail();

        Address::query()->create([
            'first_name' => 'Original',
            'last_name' => 'User',
            'street_address' => '1 rue de la Paix',
            'postal_code' => '75001',
            'city' => 'Paris',
            'country_id' => $country->id,
            'user_id' => $customer->id,
            'type' => AddressType::Shipping,
        ]);

        Livewire::test(Show::class, ['user' => $customer->id])
            ->callAction('anonymizeAction');

        $customer->refresh();

        expect($customer->phone_number)->toBeNull()
            ->and($customer->email)->toStartWith('anonymized_')
            ->and($customer->email_verified_at)->toBeNull()
            ->and($customer->addresses()->count())->toBe(0);
    });
})->group('livewire', 'customers');
