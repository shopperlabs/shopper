<?php

declare(strict_types=1);

use Shopper\Sidebar\Breadcrumbs\BreadcrumbLink;

it('constructs a link with text and url', function (): void {
    $link = new BreadcrumbLink(text: 'Brands', url: '/admin/brands');

    expect($link->text)->toBe('Brands')
        ->and($link->url)->toBe('/admin/brands')
        ->and($link->icon)->toBeNull()
        ->and($link->spa)->toBeTrue();
})->group('Breadcrumbs');

it('exposes an optional icon', function (): void {
    $link = new BreadcrumbLink(
        text: 'Brands',
        url: '/admin/brands',
        icon: 'phosphor-tag',
    );

    expect($link->icon)->toBe('phosphor-tag');
})->group('Breadcrumbs');

it('opts out of spa navigation when explicitly requested', function (): void {
    $link = new BreadcrumbLink(
        text: 'External',
        url: 'https://external.example.com',
        spa: false,
    );

    expect($link->spa)->toBeFalse();
})->group('Breadcrumbs');

it('silently falls back to `#` for unsafe URL schemes', function (string $url): void {
    $link = new BreadcrumbLink(text: 'Malicious', url: $url);

    expect($link->url)->toBe('#');
})->with([
    'javascript:alert(1)',
    'JAVASCRIPT:alert(1)',
    'data:text/html,<script>alert(1)</script>',
    'vbscript:msgbox(1)',
    '',
    '   ',
])->group('Breadcrumbs');

it('preserves safe URL schemes case-insensitively', function (string $url): void {
    $link = new BreadcrumbLink(text: 'Safe', url: $url);

    expect($link->url)->toBe($url);
})->with([
    '/admin/products',
    'https://example.com',
    'HTTP://example.com',
    '#anchor',
    '?q=search',
])->group('Breadcrumbs');
