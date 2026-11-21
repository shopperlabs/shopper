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
