<?php

declare(strict_types=1);

use Shopper\Api\ApiServiceProvider;

uses(Tests\Api\TestCase::class);

it('merges the api config under the `shopper` namespace', function (): void {
    expect(config('shopper.api.pagination.per_page'))->toBe(15)
        ->and(config('shopper.api.pagination.max_per_page'))->toBe(100);
});

it('registers the api service provider', function (): void {
    expect(app()->getLoadedProviders())->toHaveKey(ApiServiceProvider::class);
});

it('keeps the package defaults for the resources and keys a published config does not declare', function (): void {
    config()->set('shopper.api', [
        'resources' => [
            'order' => ['includes' => ['items']],
            'max_age' => 60,
        ],
    ]);

    (new ApiServiceProvider($this->app))->packageRegistered();

    expect(config('shopper.api.resources.cart.includes'))->toContain('lines.purchasable.product')
        ->and(config('shopper.api.resources.order.includes'))->toBe(['items'])
        ->and(config('shopper.api.resources.order.include_loads'))->toBe(['shippings' => ['shippings.carrier']])
        ->and(config('shopper.api.resources.order.filters'))->toBe(['status' => 'exact'])
        ->and(config('shopper.api.resources.max_age'))->toBe(60)
        ->and(config('shopper.api.pagination.per_page'))->toBe(15);
});
