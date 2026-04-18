<?php

declare(strict_types=1);

use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Breadcrumbs\BreadcrumbLink;

it('constructs a leaf crumb with just a label', function (): void {
    $crumb = new Breadcrumb(text: 'Nike Air Max 90');

    expect($crumb->text)->toBe('Nike Air Max 90')
        ->and($crumb->url)->toBeNull()
        ->and($crumb->icon)->toBeNull()
        ->and($crumb->links)->toBeNull()
        ->and($crumb->spa)->toBeTrue();
})->group('Breadcrumbs');

it('constructs a clickable section crumb with an icon', function (): void {
    $crumb = new Breadcrumb(
        text: 'Products',
        url: '/admin/products',
        icon: 'untitledui-package',
    );

    expect($crumb->text)->toBe('Products')
        ->and($crumb->url)->toBe('/admin/products')
        ->and($crumb->icon)->toBe('untitledui-package')
        ->and($crumb->links)->toBeNull()
        ->and($crumb->spa)->toBeTrue();
})->group('Breadcrumbs');

it('constructs a section crumb with sibling dropdown links', function (): void {
    $crumb = new Breadcrumb(
        text: 'Products',
        url: '/admin/products',
        icon: 'untitledui-package',
        links: [
            new BreadcrumbLink(text: 'Brands', url: '/admin/brands', icon: 'untitledui-tag'),
            new BreadcrumbLink(text: 'Categories', url: '/admin/categories', icon: 'untitledui-folder'),
        ],
    );

    expect($crumb->links)->toHaveCount(2)
        ->and($crumb->links[0])->toBeInstanceOf(BreadcrumbLink::class)
        ->and($crumb->links[0]->text)->toBe('Brands')
        ->and($crumb->links[1]->url)->toBe('/admin/categories');
})->group('Breadcrumbs');

it('opts out of spa navigation when requested', function (): void {
    $crumb = new Breadcrumb(
        text: 'External page',
        url: 'https://external.example.com',
        spa: false,
    );

    expect($crumb->spa)->toBeFalse();
})->group('Breadcrumbs');

it('silently drops unsafe URL schemes to null', function (string $url): void {
    $crumb = new Breadcrumb(text: 'Malicious', url: $url);

    expect($crumb->url)->toBeNull();
})->with([
    'javascript:alert(1)',
    'JAVASCRIPT:alert(1)',
    '  javascript:alert(1)',
    'data:text/html,<script>alert(1)</script>',
    'vbscript:msgbox(1)',
    'file:///etc/passwd',
])->group('Breadcrumbs');

it('preserves safe URL schemes case-insensitively', function (string $url): void {
    $crumb = new Breadcrumb(text: 'Safe', url: $url);

    expect($crumb->url)->toBe($url);
})->with([
    '/admin/products',
    'https://example.com',
    'http://example.com',
    'HTTPS://example.com',
    '#section',
    '?tab=general',
])->group('Breadcrumbs');

it('normalises an empty or whitespace-only URL to null', function (string $url): void {
    $crumb = new Breadcrumb(text: 'Empty', url: $url);

    expect($crumb->url)->toBeNull();
})->with(['', '   ', "\t"])->group('Breadcrumbs');
