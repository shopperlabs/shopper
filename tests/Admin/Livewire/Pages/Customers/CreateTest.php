<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Models\Country;
use Shopper\Livewire\Pages\Customers\Create;
use Shopper\Notifications\CustomerSendCredentials;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->country = Country::query()->where('cca2', 'CM')->firstOrFail();
    $this->user->givePermissionTo('add_customers');
    $this->actingAs($this->user);
});

describe(Create::class, function (): void {
    it('can render customer create component', function (): void {
        Livewire::test(Create::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.customers.create');
    });

    it('can create a customer successfully', function (): void {
        $password = 'secure-password-123';

        Livewire::test(Create::class)
            ->fillForm([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone_number' => '1234567890',
                'password' => $password,
                'password_confirmation' => $password,
                'address' => [
                    'street_address' => '123 Main St',
                    'city' => 'Douala',
                    'postal_code' => '10001',
                    'country_id' => $this->country->id,
                ],
                'opt_in' => true,
                'send_mail' => false,
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertRedirect(route('shopper.customers.index'));

        /** @var ?User $customer */
        $customer = User::query()->where('email', 'john.doe@example.com')->first();

        expect($customer)->not->toBeNull()
            ->and($customer->full_name)->toBe('John Doe')
            ->and($customer->opt_in)->toBeTrue()
            ->and($customer->email_verified_at)->not->toBeNull();
    });

    it('assigns customer role to newly created customer', function (): void {
        $password = 'secure-password-123';

        Livewire::test(Create::class)
            ->fillForm([
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'password' => $password,
                'password_confirmation' => $password,
                'address' => [
                    'street_address' => '456 Oak Ave',
                    'city' => 'Boston',
                    'postal_code' => '02101',
                    'country_id' => $this->country->id,
                ],
            ])
            ->call('store');

        $customer = User::where('email', 'jane.smith@example.com')->first();

        expect($customer->hasRole(config('shopper.admin.roles.user')))->toBeTrue();
    });

    it('creates customer address with correct type', function (): void {
        $password = 'secure-password-123';

        Livewire::test(Create::class)
            ->fillForm([
                'first_name' => 'Bob',
                'last_name' => 'Johnson',
                'email' => 'bob.johnson@example.com',
                'password' => $password,
                'password_confirmation' => $password,
                'address' => [
                    'street_address' => '789 Pine Rd',
                    'city' => 'Chicago',
                    'postal_code' => '60601',
                    'country_id' => $this->country->id,
                ],
            ])
            ->call('store');

        $customer = User::query()->where('email', 'bob.johnson@example.com')->first();
        $address = $customer->addresses()->first();

        expect($address)->not->toBeNull()
            ->and($address->first_name)->toBe('Bob')
            ->and($address->last_name)->toBe('Johnson')
            ->and($address->street_address)->toBe('789 Pine Rd')
            ->and($address->city)->toBe('Chicago')
            ->and($address->postal_code)->toBe('60601')
            ->and($address->country_id)->toBe($this->country->id)
            ->and($address->type)->toBe(AddressType::Shipping);
    });

    it('validates required fields', function (): void {
        Livewire::test(Create::class)
            ->fillForm([
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->call('store')
            ->assertHasFormErrors([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);
    });

    it('validates email format', function (): void {
        Livewire::test(Create::class)
            ->fillForm(['email' => 'invalid-email'])
            ->call('store')
            ->assertHasFormErrors(['email' => 'email']);
    });

    it('validates unique email', function (): void {
        User::factory()->create(['email' => 'existing@example.com']);

        Livewire::test(Create::class)
            ->fillForm(['email' => 'existing@example.com'])
            ->call('store')
            ->assertHasFormErrors(['email' => 'unique']);
    });

    it('validates password confirmation', function (): void {
        Livewire::test(Create::class)
            ->fillForm([
                'password' => 'password123',
                'password_confirmation' => 'different-password',
            ])
            ->call('store')
            ->assertHasFormErrors(['password' => 'confirmed']);
    });

    it('sends customer credentials notification when send_mail is true', function (): void {
        Notification::fake();

        $password = 'test-password-123';

        Livewire::test(Create::class)
            ->fillForm([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone_number' => '1234567890',
                'password' => $password,
                'password_confirmation' => $password,
                'address' => [
                    'street_address' => '123 Main St',
                    'city' => 'New York',
                    'postal_code' => '10001',
                    'country_id' => $this->country->id,
                ],
                'send_mail' => true,
            ])
            ->call('store');

        $customer = User::query()->where('email', 'john.doe@example.com')->first();

        Notification::assertSentTo(
            $customer,
            CustomerSendCredentials::class,
            fn (CustomerSendCredentials $notification): bool => $notification->password === $password
        );
    });

    it('does not send customer credentials notification when send_mail is false', function (): void {
        Notification::fake();

        $password = 'test-password-123';

        Livewire::test(Create::class)
            ->fillForm([
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'phone_number' => '0987654321',
                'password' => $password,
                'password_confirmation' => $password,
                'address' => [
                    'street_address' => '456 Oak Ave',
                    'city' => 'Boston',
                    'postal_code' => '02101',
                    'country_id' => $this->country->id,
                ],
                'send_mail' => false,
            ])
            ->call('store');

        Notification::assertNothingSent();
    });
})->group('livewire', 'customers');
