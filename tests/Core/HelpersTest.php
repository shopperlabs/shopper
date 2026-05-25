<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Setting;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    Cache::flush();
});

describe('shopper_setting', function (): void {
    it('caches only the resolved value, not the Eloquent model', function (): void {
        Setting::query()->create([
            'key' => 'name',
            'value' => 'Acme',
            'display_name' => 'Store name',
            'locked' => true,
        ]);

        shopper_setting('name');

        expect(Cache::get('shopper-setting.name'))
            ->toBe('Acme')
            ->not->toBeInstanceOf(Setting::class);
    });

    it('returns null for a missing setting without polluting cache with a model', function (): void {
        $value = shopper_setting('unknown_key');

        expect($value)->toBeNull()
            ->and(Cache::get('shopper-setting.unknown_key'))
            ->toBeNull()
            ->not->toBeInstanceOf(Setting::class);
    });

    it('respects withCache=false', function (): void {
        Setting::query()->create([
            'key' => 'name',
            'value' => 'Original',
            'display_name' => 'Store name',
            'locked' => true,
        ]);

        expect(shopper_setting('name', withCache: false))->toBe('Original');

        Setting::query()->where('key', 'name')->update(['value' => 'Updated']);

        $this->travel(2)->seconds();

        expect(shopper_setting('name', withCache: false))->toBe('Updated');
    });
});

describe('shopper_currency', function (): void {
    it('falls back to USD when default_currency_id is null', function (): void {
        expect(shopper_currency())->toBe('USD');
    });

    it('falls back to USD when the currency row was deleted', function (): void {
        Setting::query()->create([
            'key' => 'default_currency_id',
            'value' => 9999,
            'display_name' => 'Default currency',
            'locked' => true,
        ]);

        expect(shopper_currency())->toBe('USD');
    });

    it('caches the resolved currency code, not a Currency model', function (): void {
        $currency = Currency::query()->where('code', 'EUR')->firstOrFail();

        Setting::query()->create([
            'key' => 'default_currency_id',
            'value' => $currency->id,
            'display_name' => 'Default currency',
            'locked' => true,
        ]);

        shopper_currency();

        expect(Cache::get('shopper-setting.default_currency'))
            ->toBe('EUR')
            ->not->toBeInstanceOf(Currency::class);
    });
});
