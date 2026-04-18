<?php

declare(strict_types=1);

use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Breadcrumbs\Breadcrumbs;
use Shopper\Sidebar\Traits\WithBreadcrumbs;

class WithBreadcrumbsDefaultStub
{
    use WithBreadcrumbs;
}

class WithBreadcrumbsPushesStub
{
    use WithBreadcrumbs;

    public function getBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: 'Products'),
            new Breadcrumb(text: 'Nike Air Max 90'),
        ];
    }
}

beforeEach(function (): void {
    app()->instance(Breadcrumbs::class, new Breadcrumbs);
});

it('exposes an empty breadcrumbs list by default', function (): void {
    expect((new WithBreadcrumbsDefaultStub)->getBreadcrumbs())->toBe([]);
})->group('Breadcrumbs');

it('pushes breadcrumbs returned by the component into the registry on mount', function (): void {
    (new WithBreadcrumbsPushesStub)->mountWithBreadcrumbs();

    $registry = app(Breadcrumbs::class);

    expect($registry->all())->toHaveCount(2)
        ->and($registry->all()[0]->text)->toBe('Products')
        ->and($registry->all()[1]->text)->toBe('Nike Air Max 90');
})->group('Breadcrumbs');

it('preserves order across multiple component mounts', function (): void {
    (new WithBreadcrumbsPushesStub)->mountWithBreadcrumbs();
    app(Breadcrumbs::class)->push(new Breadcrumb(text: 'Deep'));

    expect(
        array_map(fn (Breadcrumb $c): string => $c->text, app(Breadcrumbs::class)->all()),
    )->toBe(['Products', 'Nike Air Max 90', 'Deep']);
})->group('Breadcrumbs');
