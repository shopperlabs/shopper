<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Zone;
use Shopper\Http\Contracts\ZoneResolver;

uses(Tests\Http\TestCase::class);

it('resolves an enabled zone by its code from the request header', function (): void {
    $zone = Zone::factory()->create(['code' => 'eu', 'is_enabled' => true]);

    $request = Request::create('/store/anything');
    $request->headers->set('X-Shopper-Zone', 'eu');

    expect(app(ZoneResolver::class)->resolve($request)?->getKey())->toBe($zone->getKey());
});

it('falls back to the configured default code when no header is present', function (): void {
    config()->set('shopper.http.zone.default_code', 'us');
    Zone::factory()->create(['code' => 'us', 'is_enabled' => true]);

    $resolved = app(ZoneResolver::class)->resolve(Request::create('/store/anything'));

    expect($resolved?->code)->toBe('us');
});

it('returns null when no zone resolves and no default is configured', function (): void {
    $request = Request::create('/store/anything');
    $request->headers->set('X-Shopper-Zone', 'unknown-code');

    expect(app(ZoneResolver::class)->resolve($request))->toBeNull();
});

it('ignores disabled zones', function (): void {
    Zone::factory()->create(['code' => 'eu', 'is_enabled' => false]);

    $request = Request::create('/store/anything');
    $request->headers->set('X-Shopper-Zone', 'eu');

    expect(app(ZoneResolver::class)->resolve($request))->toBeNull();
});

it('resolves the zone and its currency again on a cache that refuses to unserialize models', function (): void {
    config(['cache.default' => 'array', 'cache.stores.array.serialize' => true, 'cache.serializable_classes' => false]);
    Cache::forgetDriver('array');

    $currency = Currency::query()->firstOr(fn () => Currency::factory()->create());
    $zone = Zone::factory()->create(['code' => 'eu', 'is_enabled' => true, 'currency_id' => $currency->id]);

    $request = Request::create('/store/anything');
    $request->headers->set('X-Shopper-Zone', 'eu');

    foreach ([1, 2] as $attempt) {
        $resolved = app(ZoneResolver::class)->resolve($request);

        expect($resolved?->getKey())->toBe($zone->getKey())
            ->and($resolved->relationLoaded('currency'))->toBeTrue()
            ->and($resolved->currency->code)->toBe($currency->code);
    }
});
