<?php

declare(strict_types=1);

use Illuminate\Support\HtmlString;
use Shopper\ShopperPanel;

uses(Tests\Admin\TestCase::class);

it('renders a render hook returning a string', function (): void {
    $panel = new ShopperPanel;

    $panel->renderHook('test::hook', fn (): string => 'from-string');

    expect((string) $panel->getRenderHook('test::hook'))->toContain('from-string');
});

it('renders a render hook returning a `Htmlable`', function (): void {
    $panel = new ShopperPanel;

    $panel->renderHook('test::hook', fn (): HtmlString => new HtmlString('from-htmlable'));

    expect((string) $panel->getRenderHook('test::hook'))->toContain('from-htmlable');
});

it('concatenates multiple hooks of mixed return types', function (): void {
    $panel = new ShopperPanel;

    $panel->renderHook('test::hook', fn (): string => 'a-');
    $panel->renderHook('test::hook', fn (): HtmlString => new HtmlString('b'));

    expect((string) $panel->getRenderHook('test::hook'))->toContain('a-b');
});
