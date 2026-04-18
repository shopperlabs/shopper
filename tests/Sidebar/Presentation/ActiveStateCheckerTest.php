<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;
use Shopper\Sidebar\Presentation\ActiveStateChecker;

function fakeItem(bool $active, bool $authorized = true, array $children = []): Item
{
    $branchActive = $active || array_reduce(
        $children,
        fn (bool $carry, Item $child): bool => $carry || $child->isActive(),
        false,
    );

    $item = Mockery::mock(Item::class);
    $item->shouldReceive('isAuthorized')->andReturn($authorized);
    $item->shouldReceive('getItems')->andReturn(new Collection($children));
    $item->shouldReceive('isActive')->andReturn($branchActive);

    return $item;
}

function fakeGroup(array $items, bool $authorized = true): Group
{
    $group = Mockery::mock(Group::class);
    $group->shouldReceive('isAuthorized')->andReturn($authorized);
    $group->shouldReceive('getItems')->andReturn(new Collection($items));
    $group->shouldReceive('getName')->andReturn('Group');

    return $group;
}

function fakeMenu(array $groups): Menu
{
    $menu = Mockery::mock(Menu::class);
    $menu->shouldReceive('getGroups')->andReturn(new Collection($groups));

    return $menu;
}

afterEach(function (): void {
    Mockery::close();
});

it('returns null when no item is active', function (): void {
    $menu = fakeMenu([
        fakeGroup([fakeItem(active: false), fakeItem(active: false)]),
    ]);

    expect((new ActiveStateChecker)->findActivePath($menu))->toBeNull();
})->group('Breadcrumbs');

it('finds an active top-level item and returns the group + item chain', function (): void {
    $products = fakeItem(active: true);
    $menu = fakeMenu([
        $group = fakeGroup([$products, fakeItem(active: false)]),
    ]);

    $path = (new ActiveStateChecker)->findActivePath($menu);

    expect($path)->not->toBeNull()
        ->and($path->group)->toBe($group)
        ->and($path->items)->toBe([$products])
        ->and($path->topLevel())->toBe($products)
        ->and($path->leaf())->toBe($products);
})->group('Breadcrumbs');

it('chains nested active items from top-level down to the leaf', function (): void {
    $edit = fakeItem(active: true);
    $products = fakeItem(active: false, children: [$edit]);
    $menu = fakeMenu([
        fakeGroup([$products]),
    ]);

    $path = (new ActiveStateChecker)->findActivePath($menu);

    expect($path->items)->toBe([$products, $edit])
        ->and($path->leaf())->toBe($edit);
})->group('Breadcrumbs');

it('skips unauthorized groups during traversal', function (): void {
    $hiddenMatch = fakeItem(active: true);
    $visibleMatch = fakeItem(active: true);
    $menu = fakeMenu([
        fakeGroup([$hiddenMatch], authorized: false),
        $visible = fakeGroup([$visibleMatch]),
    ]);

    $path = (new ActiveStateChecker)->findActivePath($menu);

    expect($path->group)->toBe($visible)
        ->and($path->items)->toBe([$visibleMatch]);
})->group('Breadcrumbs');

it('skips unauthorized items during traversal', function (): void {
    $hidden = fakeItem(active: true, authorized: false);
    $visible = fakeItem(active: true);
    $menu = fakeMenu([
        fakeGroup([$hidden, $visible]),
    ]);

    $path = (new ActiveStateChecker)->findActivePath($menu);

    expect($path->items)->toBe([$visible]);
})->group('Breadcrumbs');

it('skips unauthorized children when chaining to the leaf', function (): void {
    $hiddenLeaf = fakeItem(active: true, authorized: false);
    $parent = fakeItem(active: true, children: [$hiddenLeaf]);
    $menu = fakeMenu([
        fakeGroup([$parent]),
    ]);

    $path = (new ActiveStateChecker)->findActivePath($menu);

    expect($path->items)->toBe([$parent])
        ->and($path->leaf())->toBe($parent);
})->group('Breadcrumbs');
