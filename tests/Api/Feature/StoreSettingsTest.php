<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Setting;

uses(Tests\Api\TestCase::class);

function setting(string $key, mixed $value): void
{
    $setting = Setting::query()->firstWhere('key', $key);

    if ($setting instanceof Setting) {
        $setting->update(['value' => $value]);
    } else {
        Setting::factory()->create(['key' => $key, 'value' => $value, 'display_name' => $key]);
    }

    Cache::forget("shopper-setting.{$key}");
}

it('exposes only the allowlisted setting keys', function (): void {
    setting('name', 'Shopper Store');
    setting('admin_theme', 'dark');
    setting('setup_guide_done', true);

    $attributes = $this->getJson('/store/settings')
        ->assertOk()
        ->assertJsonPath('data.type', 'settings')
        ->assertJsonPath('data.id', 'store')
        ->assertJsonPath('data.attributes.name', 'Shopper Store')
        ->json('data.attributes');

    expect($attributes)->not->toHaveKey('admin_theme')
        ->and($attributes)->not->toHaveKey('setup_guide_done')
        ->and($attributes)->not->toHaveKey('country_id')
        ->and($attributes)->not->toHaveKey('default_currency_id');
});

it('reads the allowlist in a constant number of queries whatever its size', function (): void {
    $reads = function (): int {
        DB::flushQueryLog();

        $this->getJson('/store/settings')->assertOk();

        return collect(DB::getQueryLog())
            ->filter(fn (array $query): bool => str_contains((string) $query['query'], shopper_table('settings')))
            ->count();
    };

    DB::enableQueryLog();

    $baseline = $reads();

    config(['shopper.api.settings.expose' => [
        ...(array) config('shopper.api.settings.expose'),
        'shipping_notice',
        'support_hours',
        'vat_number',
    ]]);

    expect($reads())->toBe($baseline)
        ->and($this->getJson('/store/settings')->json('data.attributes'))
        ->toHaveKeys(['shipping_notice', 'support_hours', 'vat_number']);
});

it('returns null for a setting that was never filled in', function (): void {
    $this->getJson('/store/settings')
        ->assertOk()
        ->assertJsonPath('data.attributes.legal_name', null)
        ->assertJsonPath('data.attributes.phone_number', null)
        ->assertJsonPath('data.attributes.logo', null)
        ->assertJsonPath('data.attributes.country', null)
        ->assertJsonPath('data.attributes.social_links', []);
});

it('resolves the country and currencies to their public codes', function (): void {
    $france = Country::query()->where('cca2', 'FR')->firstOrFail();
    $euro = Currency::query()->where('code', 'EUR')->firstOrFail();
    $dollar = Currency::query()->where('code', 'USD')->firstOrFail();

    setting('country_id', $france->id);
    setting('default_currency_id', $euro->id);
    setting('currencies', [$euro->id, $dollar->id]);

    $this->getJson('/store/settings')
        ->assertOk()
        ->assertJsonPath('data.attributes.country.code', 'FR')
        ->assertJsonPath('data.attributes.country.name', $france->translated_name)
        ->assertJsonPath('data.attributes.default_currency', 'EUR')
        ->assertJsonPath('data.attributes.currencies', ['EUR', 'USD']);
});

it('serves the logo and cover as absolute urls rather than disk paths', function (): void {
    setting('logo', 'settings/logo.png');

    $url = $this->getJson('/store/settings')->assertOk()->json('data.attributes.logo.url');

    expect($url)->toStartWith('http')
        ->and($url)->toEndWith('settings/logo.png');
});

it('exposes every platform of the enum with its label, in the admin order', function (): void {
    setting('social_links', [
        ['platform' => 'tiktok', 'url' => 'https://tiktok.com/@shopper'],
        ['platform' => 'x', 'url' => 'https://x.com/shopper'],
        ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/shopper'],
        ['platform' => 'pinterest', 'url' => 'https://pinterest.com/shopper'],
        ['platform' => 'unknown', 'url' => 'https://example.com'],
        ['platform' => 'youtube', 'url' => ''],
    ]);

    $links = $this->getJson('/store/settings')->assertOk()->json('data.attributes.social_links');

    expect($links)->toBe([
        ['platform' => 'tiktok', 'url' => 'https://tiktok.com/@shopper', 'label' => 'TikTok'],
        ['platform' => 'x', 'url' => 'https://x.com/shopper', 'label' => 'X (Twitter)'],
        ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/shopper', 'label' => 'LinkedIn'],
        ['platform' => 'pinterest', 'url' => 'https://pinterest.com/shopper', 'label' => 'Pinterest'],
    ]);
});

it('answers 304 when the client already holds the current version', function (): void {
    setting('name', 'Shopper Store');

    $response = $this->getJson('/store/settings')->assertOk();
    $etag = $response->headers->get('ETag');

    expect($etag)->not->toBeNull()
        ->and($response->headers->get('Cache-Control'))->toContain('max-age=300')
        ->and($response->headers->get('Vary'))->toContain('Accept-Language');

    $this->getJson('/store/settings', ['If-None-Match' => $etag])->assertStatus(304);

    setting('name', 'Renamed Store');

    $this->getJson('/store/settings', ['If-None-Match' => $etag])
        ->assertOk()
        ->assertJsonPath('data.attributes.name', 'Renamed Store');
});
