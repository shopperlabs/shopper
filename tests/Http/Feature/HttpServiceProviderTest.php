<?php

declare(strict_types=1);

use Illuminate\Support\Facades\RateLimiter;
use Shopper\Http\Enum\RateLimit;
use Shopper\Http\Facades\ShopperApi;
use Shopper\Http\ShopperApiRouter;

uses(Tests\Http\TestCase::class);

it('merges the http config under the `shopper` namespace', function (): void {
    expect(config('shopper.http.prefix'))->toBe('store')
        ->and(config('shopper.http.zone.header'))->toBe('X-Shopper-Zone');
});

it('registers the store middleware groups', function (): void {
    $groups = app('router')->getMiddlewareGroups();

    expect($groups)
        ->toHaveKey('shopper:store')
        ->toHaveKey('shopper:store-auth');
});

it('defines a named rate limiter for every `RateLimit` case', function (): void {
    foreach (RateLimit::cases() as $limit) {
        expect(RateLimiter::limiter($limit))->not->toBeNull();
    }
});

it('reads the configured max attempts through the `RateLimit` enum', function (): void {
    config()->set('shopper.http.rate_limiters.'.RateLimit::Auth->value, 5);

    expect(RateLimit::Auth->maxAttempts())->toBe(5);
});

it('binds the api router and exposes the configured prefix', function (): void {
    expect(app(ShopperApiRouter::class))->toBeInstanceOf(ShopperApiRouter::class)
        ->and(ShopperApi::prefix())->toBe('store');
});
