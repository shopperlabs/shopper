<?php

declare(strict_types=1);

use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Setting;
use Shopper\Livewire\Pages\Settings\Currencies;
use Shopper\Livewire\Pages\Settings\General;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('system.settings');
    $this->actingAs($this->user);
});

describe(General::class, function (): void {
    it('can render general settings component', function (): void {
        Livewire::test(General::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.general');
    });

    it('initializes form data with settings', function (): void {
        Setting::query()->updateOrCreate(
            ['key' => 'name'],
            ['value' => 'Test Store', 'display_name' => 'Store Name']
        );

        $component = Livewire::test(General::class);

        expect($component->get('data'))->toBeArray()
            ->and($component->get('data')['name'] ?? null)->toBe('Test Store');
    });

    it('initializes data property as array', function (): void {
        $component = Livewire::test(General::class);

        expect($component->get('data'))->toBeArray();
    });

    it('renders the country options again on a cache that refuses to unserialize models', function (): void {
        config(['cache.default' => 'array', 'cache.stores.array.serialize' => true, 'cache.serializable_classes' => false]);
        Cache::forgetDriver('array');
        $country = Country::query()->firstOr(fn () => Country::factory()->create());

        foreach ([1, 2] as $render) {
            Livewire::test(General::class)->assertOk()->assertSee($country->translated_name);
        }
    });

    it('stores the logo on the configured media disk', function (): void {
        $disk = config('shopper.media.storage.disk_name');

        Storage::fake($disk);

        $country = Country::query()->firstOr(fn () => Country::factory()->create());
        $currency = Currency::query()->firstOr(fn () => Currency::factory()->create());

        Livewire::test(General::class)
            ->fillForm([
                'name' => 'Test Store',
                'email' => 'store@example.com',
                'legal_name' => 'Test Store LLC',
                'street_address' => 'Akwa Avenue 34',
                'city' => 'Douala',
                'postal_code' => '00237',
                'country_id' => $country->id,
                'currencies' => [$currency->id],
                'default_currency_id' => $currency->id,
                'logo' => UploadedFile::fake()->image('logo.png'),
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        $logo = Setting::query()->where('key', 'logo')->value('value');

        expect($logo)->not->toBeNull();

        Storage::disk($disk)->assertExists($logo);
    });

    it('locks the new default currency even when a concurrent request cached the previous one before the save committed', function (): void {
        setupCurrencies(['USD', 'EUR'], 'USD');
        $usd = Currency::query()->where('code', 'USD')->sole();
        $eur = Currency::query()->where('code', 'EUR')->sole();
        $country = Country::query()->firstOr(fn () => Country::factory()->create());

        Setting::saved(function (Setting $setting) use ($usd): void {
            if ($setting->key === 'social_links') {
                Cache::put('shopper-setting.default_currency_id', $usd->id);
            }
        });

        Livewire::test(General::class)
            ->fillForm([
                'name' => 'Test Store',
                'email' => 'store@example.com',
                'legal_name' => 'Test Store LLC',
                'street_address' => 'Akwa Avenue 34',
                'city' => 'Douala',
                'postal_code' => '00237',
                'country_id' => $country->id,
                'currencies' => [$usd->id, $eur->id],
                'default_currency_id' => $eur->id,
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        Livewire::test(Currencies::class)
            ->loadTable()
            ->assertTableColumnExists('is_enabled', fn (ToggleColumn $column): bool => $column->isDisabled(), $eur)
            ->assertTableColumnExists('is_enabled', fn (ToggleColumn $column): bool => ! $column->isDisabled(), $usd);
    });

    it('rejects an SVG file for the logo and cover uploads', function (): void {
        Storage::fake(config('shopper.media.storage.disk_name'));

        $svg = UploadedFile::fake()->createWithContent(
            'payload.svg',
            '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"></svg>'
        );

        Livewire::test(General::class)
            ->fillForm([
                'logo' => $svg,
                'cover' => $svg,
            ])
            ->call('store')
            ->assertHasFormErrors(['logo', 'cover']);
    });
})->group('livewire', 'settings');
