<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\User;
use Shopper\Facades\Shopper;
use Shopper\Livewire\Components\Initialization\InitializationWizard;
use Shopper\Livewire\Components\Initialization\Steps\StoreAddress;
use Shopper\Livewire\Components\Initialization\Steps\StoreInformation;
use Shopper\Livewire\Components\Initialization\Steps\StoreSocialLink;
use Shopper\Livewire\Pages\Initialization;

beforeEach(function (): void {
    $this->prefix = Shopper::prefix();
});

it('only admin can not access dashboard', function (): void {
    $this->actingAs(User::factory()->create());

    $this->get($this->prefix . '/dashboard')
        ->assertForbidden();
});

it('can not access dashboard with unfinished configuration', function (): void {
    $this->asAdmin();

    $this->get($this->prefix . '/dashboard')
        ->assertRedirect($this->prefix . '/initialize');

    expect(shopper_setting('email', false))
        ->toBeNull()
        ->and(shopper_setting('street_address', false))
        ->toBeNull();
});

it('can view first initialization wizard steps', function (): void {
    $this->asAdmin();

    $this->get($this->prefix . '/initialize')
        ->assertSeeLivewire(Initialization::class)
        ->assertSuccessful();

    $wizard = Livewire::test(InitializationWizard::class);
    $wizard->assertSee('initialize-store-information');
});

it('can save settings on wizard to access dashboard', function (): void {
    $this->asAdmin();

    $this->get($this->prefix . '/initialize')
        ->assertSeeLivewire(Initialization::class)
        ->assertSuccessful();

    $wizard = Livewire::test(InitializationWizard::class);
    $wizard->assertSee('initialize-store-information');

    Livewire::test(StoreInformation::class)
        ->fillForm([
            'name' => 'My store',
            'email' => 'mystore@example.com',
            'country_id' => Country::query()->first()->id,
            'currencies' => $currencies = Currency::query()
                ->inRandomOrder()
                ->select('id', 'code')
                ->limit(2)
                ->pluck('id')
                ->toArray(),
            'default_currency_id' => $currencies[0],
            'about' => '',
        ])
        ->call('save')
        ->call('nextStep')
        ->assertHasNoFormErrors();

    expect(shopper_setting('email'))
        ->toBe('mystore@example.com');

    $wizard->assertSee('initialize-store-address');

    Livewire::test(StoreAddress::class)
        ->fillForm([
            'street_address' => '34 Douala, Bonamoussadi',
            'postal_code' => '00237',
            'city' => 'Douala',
            'phone_number' => '',
        ])
        ->call('save')
        ->call('nextStep')
        ->assertHasNoFormErrors();

    $wizard->assertSee('initialize-store-social-link');

    Livewire::test(StoreSocialLink::class)
        ->fillForm([])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertRedirect($this->prefix . '/dashboard');

    expect(Inventory::query()->count())
        ->toBe(1);
});

it('can validate all `required` fields in store information step', function (): void {
    $this->asAdmin();

    $wizard = Livewire::test(InitializationWizard::class);
    $wizard->assertSee('initialize-store-information');

    Livewire::test(StoreInformation::class)
        ->fillForm([
            'name' => '',
            'email' => '',
            'country_id' => '',
            'currencies' => [],
            'default_currency_id' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['name', 'email', 'default_currency_id', 'currencies']);
});

it('can validate all `required` fields in store address step', function (): void {
    $this->asAdmin();

    $wizard = Livewire::test(InitializationWizard::class);
    $wizard->assertSee('initialize-store-address');

    Livewire::test(StoreAddress::class)
        ->fillForm([
            'street_address' => '',
            'postal_code' => '',
            'city' => '',
            'phone_number' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['street_address', 'postal_code', 'city']);
});
