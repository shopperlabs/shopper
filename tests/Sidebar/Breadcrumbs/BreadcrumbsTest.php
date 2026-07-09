<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Breadcrumbs\BreadcrumbLink;
use Shopper\Sidebar\Breadcrumbs\Breadcrumbs;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

function crumbItem(
    string $name,
    string $url,
    ?string $icon = null,
    bool $active = false,
    bool $authorized = true,
    bool $spa = true,
    array $children = [],
): Item {
    $branchActive = $active || array_reduce(
        $children,
        fn (bool $carry, Item $child): bool => $carry || $child->isActive(),
        false,
    );

    $item = Mockery::mock(Item::class);
    $item->shouldReceive('getName')->andReturn($name);
    $item->shouldReceive('getUrl')->andReturn($url);
    $item->shouldReceive('getIcon')->andReturn($icon);
    $item->shouldReceive('withSpa')->andReturn($spa);
    $item->shouldReceive('isAuthorized')->andReturn($authorized);
    $item->shouldReceive('getItems')->andReturn(new Collection($children));
    $item->shouldReceive('isActive')->andReturn($branchActive);

    return $item;
}

function crumbGroup(array $items, bool $authorized = true): Group
{
    $group = Mockery::mock(Group::class);
    $group->shouldReceive('isAuthorized')->andReturn($authorized);
    $group->shouldReceive('getItems')->andReturn(new Collection($items));

    return $group;
}

function crumbMenu(array $groups): Menu
{
    $menu = Mockery::mock(Menu::class);
    $menu->shouldReceive('getGroups')->andReturn(new Collection($groups));

    return $menu;
}

afterEach(function (): void {
    Mockery::close();
});

beforeEach(function (): void {
    $this->breadcrumbs = new Breadcrumbs;
});

it('starts empty', function (): void {
    expect($this->breadcrumbs->all())->toBe([]);
})->group('Breadcrumbs');

it('pushes a single breadcrumb', function (): void {
    $crumb = new Breadcrumb(text: 'Products', url: '/admin/products');

    $this->breadcrumbs->push($crumb);

    expect($this->breadcrumbs->all())->toBe([$crumb]);
})->group('Breadcrumbs');

it('preserves push order across multiple breadcrumbs', function (): void {
    $products = new Breadcrumb(text: 'Products', url: '/admin/products');
    $brands = new Breadcrumb(text: 'Brands', url: '/admin/brands');
    $nike = new Breadcrumb(text: 'Nike');

    $this->breadcrumbs->push($products);
    $this->breadcrumbs->push($brands);
    $this->breadcrumbs->push($nike);

    expect($this->breadcrumbs->all())->toBe([$products, $brands, $nike]);
})->group('Breadcrumbs');

it('prepends a breadcrumb before previously pushed ones', function (): void {
    $variant = new Breadcrumb(text: '45');
    $product = new Breadcrumb(text: 'Nike Air Max 90', url: '/admin/products/1/edit');

    $this->breadcrumbs->push($variant);
    $this->breadcrumbs->prepend($product);

    expect($this->breadcrumbs->all())->toBe([$product, $variant]);
})->group('Breadcrumbs');

it('empties the stack on reset', function (): void {
    $this->breadcrumbs->push(new Breadcrumb(text: 'Products'));
    $this->breadcrumbs->push(new Breadcrumb(text: 'Brands'));

    $this->breadcrumbs->reset();

    expect($this->breadcrumbs->all())->toBe([]);
})->group('Breadcrumbs');

it('can be reused after reset', function (): void {
    $this->breadcrumbs->push(new Breadcrumb(text: 'Old'));
    $this->breadcrumbs->reset();

    $fresh = new Breadcrumb(text: 'Fresh');
    $this->breadcrumbs->push($fresh);

    expect($this->breadcrumbs->all())->toBe([$fresh]);
})->group('Breadcrumbs');

it('builds only pushed crumbs when no active path is found', function (): void {
    $menu = crumbMenu([
        crumbGroup([crumbItem('Products', '/admin/products')]),
    ]);

    $this->breadcrumbs->push($pushed = new Breadcrumb(text: 'Custom page'));

    expect($this->breadcrumbs->build($menu))->toBe([$pushed]);
})->group('Breadcrumbs');

it('auto-generates a top-level crumb with sibling dropdown from the active sidebar item', function (): void {
    $menu = crumbMenu([
        crumbGroup([
            crumbItem('Products', '/admin/products', 'phosphor-package', active: true),
            crumbItem('Brands', '/admin/brands', 'phosphor-tag'),
            crumbItem('Categories', '/admin/categories', 'phosphor-folder'),
        ]),
    ]);

    $crumbs = $this->breadcrumbs->build($menu);

    expect($crumbs)->toHaveCount(1)
        ->and($crumbs[0]->text)->toBe('Products')
        ->and($crumbs[0]->url)->toBe('/admin/products')
        ->and($crumbs[0]->icon)->toBe('phosphor-package')
        ->and($crumbs[0]->spa)->toBeTrue()
        ->and($crumbs[0]->links)->toHaveCount(2)
        ->and($crumbs[0]->links[0])->toBeInstanceOf(BreadcrumbLink::class)
        ->and($crumbs[0]->links[0]->text)->toBe('Brands')
        ->and($crumbs[0]->links[1]->text)->toBe('Categories');
})->group('Breadcrumbs');

it('returns an empty sibling list when the active item is alone in its group', function (): void {
    $menu = crumbMenu([
        crumbGroup([
            crumbItem('Reviews', '/admin/reviews', active: true),
        ]),
    ]);

    $crumbs = $this->breadcrumbs->build($menu);

    expect($crumbs)->toHaveCount(1)
        ->and($crumbs[0]->text)->toBe('Reviews')
        ->and($crumbs[0]->links)->toBe([]);
})->group('Breadcrumbs');

it('adds nested active items without sibling dropdown', function (): void {
    $edit = crumbItem('Edit', '/admin/products/edit', active: true);
    $menu = crumbMenu([
        crumbGroup([
            crumbItem('Products', '/admin/products', 'phosphor-package', children: [$edit]),
            crumbItem('Brands', '/admin/brands'),
        ]),
    ]);

    $crumbs = $this->breadcrumbs->build($menu);

    expect($crumbs)->toHaveCount(2)
        ->and($crumbs[0]->text)->toBe('Products')
        ->and($crumbs[0]->links)->toHaveCount(1)
        ->and($crumbs[0]->links[0]->text)->toBe('Brands')
        ->and($crumbs[1]->text)->toBe('Edit')
        ->and($crumbs[1]->links)->toBeNull();
})->group('Breadcrumbs');

it('appends pushed crumbs after auto-generated ones', function (): void {
    $menu = crumbMenu([
        crumbGroup([
            crumbItem('Products', '/admin/products', active: true),
        ]),
    ]);

    $this->breadcrumbs->push(new Breadcrumb(text: 'Nike Air Max 90'));

    $crumbs = $this->breadcrumbs->build($menu);

    expect($crumbs)->toHaveCount(2)
        ->and($crumbs[0]->text)->toBe('Products')
        ->and($crumbs[1]->text)->toBe('Nike Air Max 90');
})->group('Breadcrumbs');

it('inherits the spa flag from the active sidebar item', function (): void {
    $menu = crumbMenu([
        crumbGroup([
            crumbItem('Products', '/admin/products', active: true, spa: false),
        ]),
    ]);

    expect($this->breadcrumbs->build($menu)[0]->spa)->toBeFalse();
})->group('Breadcrumbs');

it('lets pushed crumbs override auto-detected ones by URL match', function (): void {
    $menu = crumbMenu([
        crumbGroup([
            crumbItem('Site', '/admin/settings', 'phosphor-faders', active: true),
        ]),
    ]);

    $this->breadcrumbs->push(new Breadcrumb(
        text: 'Settings',
        url: '/admin/settings',
        icon: 'phosphor-gear',
        links: [new BreadcrumbLink(text: 'General', url: '/admin/settings/general')],
    ));

    $crumbs = $this->breadcrumbs->build($menu);

    expect($crumbs)->toHaveCount(1)
        ->and($crumbs[0]->text)->toBe('Settings')
        ->and($crumbs[0]->icon)->toBe('phosphor-gear')
        ->and($crumbs[0]->links)->toHaveCount(1);
})->group('Breadcrumbs');

it('filters unauthorized siblings from the dropdown', function (): void {
    $menu = crumbMenu([
        crumbGroup([
            crumbItem('Products', '/admin/products', active: true),
            crumbItem('Brands', '/admin/brands'),
            crumbItem('Hidden', '/admin/hidden', authorized: false),
        ]),
    ]);

    $links = $this->breadcrumbs->build($menu)[0]->links;

    expect($links)->toHaveCount(1)
        ->and($links[0]->text)->toBe('Brands');
})->group('Breadcrumbs');
