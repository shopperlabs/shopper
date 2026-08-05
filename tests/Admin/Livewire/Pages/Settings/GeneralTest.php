<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Setting;
use Shopper\Livewire\Pages\Settings\General;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('access_setting');
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

    it('rejects SVG logo and cover uploads to prevent stored XSS from same-origin media', function (): void {
        Storage::fake(config('shopper.media.storage.disk_name'));

        Livewire::test(General::class)
            ->fillForm([
                'logo' => UploadedFile::fake()->create('logo.svg', 10, 'image/svg+xml'),
                'cover' => UploadedFile::fake()->create('cover.svg', 10, 'image/svg+xml'),
            ])
            ->call('store')
            ->assertHasFormErrors(['logo', 'cover']);
    });
})->group('livewire', 'settings');
