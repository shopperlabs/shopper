<?php

declare(strict_types=1);

use Shopper\Sidebar\Breadcrumbs\UrlSanitizer;

it('returns null for null input', function (): void {
    expect(UrlSanitizer::sanitize(null))->toBeNull();
})->group('Breadcrumbs');

it('returns null for empty or whitespace-only input', function (string $url): void {
    expect(UrlSanitizer::sanitize($url))->toBeNull();
})->with(['', '   ', "\t", "\n"])->group('Breadcrumbs');

it('strips unsafe schemes', function (string $url): void {
    expect(UrlSanitizer::sanitize($url))->toBeNull();
})->with([
    'javascript:alert(1)',
    'JAVASCRIPT:alert(1)',
    '  javascript:alert(1)',
    'data:text/html,<script>alert(1)</script>',
    'vbscript:msgbox(1)',
    'file:///etc/passwd',
    'ftp://example.com',
])->group('Breadcrumbs');

it('preserves safe URLs unchanged', function (string $url): void {
    expect(UrlSanitizer::sanitize($url))->toBe($url);
})->with([
    '/admin/products',
    '/',
    'https://example.com/path?q=1',
    'http://example.com',
    'HTTPS://example.com',
    '#section',
    '?tab=general',
])->group('Breadcrumbs');
