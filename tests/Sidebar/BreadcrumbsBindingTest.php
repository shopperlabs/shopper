<?php

declare(strict_types=1);

use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Breadcrumbs\Breadcrumbs;

uses(Tests\Admin\TestCase::class);

it('resolves the breadcrumbs registry as a scoped singleton within a single request', function (): void {
    expect(app(Breadcrumbs::class))->toBe(app(Breadcrumbs::class));
})->group('Breadcrumbs');

it('keeps pushed breadcrumbs on the scoped instance within a single request', function (): void {
    app(Breadcrumbs::class)->push(new Breadcrumb(text: 'Scoped'));

    expect(app(Breadcrumbs::class)->all())->toHaveCount(1);
})->group('Breadcrumbs');

it('flushes the scoped instance when the container is flushed between requests', function (): void {
    app(Breadcrumbs::class)->push(new Breadcrumb(text: 'From previous request'));

    app()->forgetScopedInstances();

    expect(app(Breadcrumbs::class)->all())->toBe([]);
})->group('Breadcrumbs');
