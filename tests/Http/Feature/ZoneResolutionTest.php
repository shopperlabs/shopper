<?php

declare(strict_types=1);

use Illuminate\Http\Request;
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
