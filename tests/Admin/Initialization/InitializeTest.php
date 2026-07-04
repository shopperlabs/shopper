<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Setting;
use Shopper\Facades\Shopper;
use Shopper\Livewire\Components\Initialization\InitializationWizard;
use Shopper\Livewire\Pages\Initialization;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->prefix = Shopper::prefix();
});

it('only admin can not access dashboard', function (): void {
    $this->actingAs(User::factory()->create());

    $this->get($this->prefix.'/dashboard')
        ->assertRedirect($this->prefix.'/forbidden');
});

it('can not access dashboard with unfinished configuration', function (): void {
    $this->asAdmin();

    $this->get($this->prefix.'/dashboard')
        ->assertRedirect($this->prefix.'/initialize');

    expect(shopper_setting('email', false))
        ->toBeNull()
        ->and(shopper_setting('street_address', false))
        ->toBeNull();
});

it('can view the initialization wizard with all steps', function (): void {
    $this->asAdmin();

    $this->get($this->prefix.'/initialize')
        ->assertSeeLivewire(Initialization::class)
        ->assertSuccessful();

    Livewire::test(InitializationWizard::class)
        ->assertSuccessful()
        ->assertSee(__('shopper::pages/onboarding.step_one_title'))
        ->assertSee(__('shopper::pages/onboarding.step_two_title'))
        ->assertSee(__('shopper::pages/onboarding.step_tree_title'));
});

it('restores partial state from existing settings on `mount()`', function (): void {
    $this->asAdmin();

    Setting::query()->insert([
        ['key' => 'name', 'value' => json_encode('Restored Store'), 'display_name' => 'Store name', 'locked' => false],
        ['key' => 'email', 'value' => json_encode('restored@example.com'), 'display_name' => 'Email', 'locked' => false],
        ['key' => 'city', 'value' => json_encode('Douala'), 'display_name' => 'City', 'locked' => false],
    ]);

    Livewire::test(InitializationWizard::class)
        ->assertSet('data.name', 'Restored Store')
        ->assertSet('data.email', 'restored@example.com')
        ->assertSet('data.city', 'Douala');
});

it('can save settings and create a default inventory through the wizard', function (): void {
    $this->asAdmin();

    $currencies = Currency::query()
        ->orderBy('id')
        ->limit(2)
        ->pluck('id')
        ->toArray();

    Livewire::test(InitializationWizard::class)
        ->fillForm([
            'name' => 'My store',
            'email' => 'mystore@example.com',
            'country_id' => Country::query()->first()->id,
            'currencies' => $currencies,
            'default_currency_id' => $currencies[0],
            'street_address' => '34 Douala, Bonamoussadi',
            'postal_code' => '00237',
            'city' => 'Douala',
            'phone_number' => '+237 600 000 000',
            'social_links' => [
                ['platform' => 'facebook', 'url' => 'https://facebook.com/mystore'],
                ['platform' => 'instagram', 'url' => 'https://instagram.com/mystore'],
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertRedirect($this->prefix.'/dashboard');

    expect(shopper_setting('name'))
        ->toBe('My store')
        ->and(shopper_setting('email'))
        ->toBe('mystore@example.com')
        ->and(shopper_setting('street_address'))
        ->toBe('34 Douala, Bonamoussadi')
        ->and(shopper_setting('city'))
        ->toBe('Douala')
        ->and(Inventory::query()->count())
        ->toBe(1)
        ->and(Inventory::query()->where('is_default', true)->exists())
        ->toBeTrue();
});

it('does not create a duplicate `Inventory` when `save()` runs twice', function (): void {
    $this->asAdmin();

    $currencies = Currency::query()->orderBy('id')->limit(1)->pluck('id')->toArray();

    $state = [
        'name' => 'Idempotent store',
        'email' => 'idem@example.com',
        'country_id' => Country::query()->first()->id,
        'currencies' => $currencies,
        'default_currency_id' => $currencies[0],
        'street_address' => '1 Test St',
        'postal_code' => '12345',
        'city' => 'Testville',
    ];

    Livewire::test(InitializationWizard::class)
        ->fillForm($state)
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(InitializationWizard::class)
        ->fillForm($state)
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Inventory::query()->where('is_default', true)->count())->toBe(1);
});

it('validates required fields on the information step', function (): void {
    $this->asAdmin();

    Livewire::test(InitializationWizard::class)
        ->fillForm([
            'name' => '',
            'email' => '',
            'country_id' => '',
            'currencies' => [],
            'default_currency_id' => '',
        ])
        ->call('save')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
            'currencies' => 'required',
            'default_currency_id' => 'required',
        ]);
});

it('validates required fields on the address step', function (): void {
    $this->asAdmin();

    $currencies = Currency::query()->orderBy('id')->limit(1)->pluck('id')->toArray();

    Livewire::test(InitializationWizard::class)
        ->fillForm([
            'name' => 'My store',
            'email' => 'mystore@example.com',
            'country_id' => Country::query()->first()->id,
            'currencies' => $currencies,
            'default_currency_id' => $currencies[0],
            'street_address' => '',
            'postal_code' => '',
            'city' => '',
        ])
        ->call('save')
        ->assertHasFormErrors([
            'street_address' => 'required',
            'postal_code' => 'required',
            'city' => 'required',
        ]);
});

it('rejects an invalid email on the information step', function (): void {
    $this->asAdmin();

    Livewire::test(InitializationWizard::class)
        ->fillForm([
            'name' => 'My store',
            'email' => 'not-an-email',
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'email']);
});

it('rejects a `default_currency_id` not present in selected `currencies`', function (): void {
    $this->asAdmin();

    $currencies = Currency::query()->orderBy('id')->limit(2)->pluck('id')->toArray();

    Livewire::test(InitializationWizard::class)
        ->fillForm([
            'name' => 'My store',
            'email' => 'mystore@example.com',
            'country_id' => Country::query()->first()->id,
            'currencies' => [$currencies[0]],
            'default_currency_id' => $currencies[1],
            'street_address' => '1 Test St',
            'postal_code' => '12345',
            'city' => 'Testville',
        ])
        ->call('save')
        ->assertHasFormErrors(['default_currency_id']);
});

it('rejects a store `name` exceeding 100 characters', function (): void {
    $this->asAdmin();

    Livewire::test(InitializationWizard::class)
        ->fillForm(['name' => str_repeat('a', 101)])
        ->call('save')
        ->assertHasFormErrors(['name' => 'max']);
});

it('rejects a malformed social link `URL`', function (): void {
    $this->asAdmin();

    Livewire::test(InitializationWizard::class)
        ->fillForm([
            'social_links' => [
                ['platform' => 'facebook', 'url' => 'not-a-url'],
            ],
        ])
        ->call('save')
        ->assertHasFormErrors(['social_links.0.url' => 'url']);
});

it('rejects a social link `URL` that does not match its platform', function (): void {
    $this->asAdmin();

    Livewire::test(InitializationWizard::class)
        ->fillForm([
            'social_links' => [
                ['platform' => 'facebook', 'url' => 'https://instagram.com/mystore'],
            ],
        ])
        ->call('save')
        ->assertHasFormErrors(['social_links.0.url']);
});

it('forbids a non-admin user from mounting the wizard component', function (): void {
    $this->actingAs(User::factory()->create(), config('shopper.auth.guard'));

    Livewire::test(InitializationWizard::class)
        ->assertStatus(403);
});

it('forbids a non-admin user from calling save', function (): void {
    $this->asAdmin();

    $component = Livewire::test(InitializationWizard::class);

    $this->actingAs(User::factory()->create(), config('shopper.auth.guard'));

    $component->call('save')->assertStatus(403);
});

it('ignores unknown keys submitted in the form state', function (): void {
    $this->asAdmin();

    $country = Country::query()->first();
    [$first, $second] = Currency::query()->orderBy('id')->limit(2)->pluck('id')->all();

    Livewire::test(InitializationWizard::class)
        ->fillForm([
            'name' => 'Acme',
            'email' => 'hello@acme.test',
            'country_id' => $country->id,
            'currencies' => [$first, $second],
            'default_currency_id' => $first,
            'street_address' => 'Akwa Avenue 34',
            'postal_code' => '00237',
            'city' => 'Douala',
            'phone_number' => '+237600000000',
        ])
        ->set('data.is_admin', true)
        ->set('data.role', 'super-admin')
        ->call('save')
        ->assertHasNoErrors();

    expect(Setting::query()->where('key', 'is_admin')->exists())->toBeFalse()
        ->and(Setting::query()->where('key', 'role')->exists())->toBeFalse()
        ->and(Setting::query()->where('key', 'name')->value('value'))->toBe('Acme');
});
