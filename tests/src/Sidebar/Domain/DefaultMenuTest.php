<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Menu;
use Shopper\Sidebar\Domain\DefaultGroup;
use Shopper\Sidebar\Domain\DefaultMenu;
use Shopper\Tests\Sidebar\Stubs\MenuStub;

beforeEach(function (): void {
    $this->container = Mockery::mock(Container::class);
    $this->menu = new DefaultMenu($this->container);
});

function asContainerMock(MockInterface $container, ?string $name = null, ?int $weight = null): Group
{
    $group = Mockery::mock(Group::class);
    $group->shouldReceive('setName');
    $group->shouldReceive('weight');
    $group->shouldReceive('getName')->andReturn($name);
    $group->shouldReceive('getWeight')->andReturn($weight);

    $container->shouldReceive('make')->with(Group::class)->andReturn($group);

    return $group;
}

it('can be instantiate new item', function (): void {
    $menu = new DefaultMenu($this->container);

    expect($menu)->toBeInstanceOf(DefaultMenu::class);
})->group('Domain');

it('can have custom menu', function (): void {
    $menu = new MenuStub($this->container);

    expect($menu)->toBeInstanceOf(Menu::class);
})->group('Domain');

it('menu can be cached', function (): void {
    $group = new DefaultGroup($this->container);
    $group->setName('test');
    $group->weight(1);

    $group2 = new DefaultGroup($this->container);
    $group2->setName('test 2');
    $group2->weight(2);

    $this->menu->addGroup($group);
    $this->menu->addGroup($group2);

    $serialized = serialize($this->menu);
    $unserialized = unserialize($serialized);

    expect($unserialized)->toBeInstanceOf(Menu::class)
        ->and($unserialized->getGroups())->toBeInstanceOf(Collection::class)
        ->and($unserialized->getGroups()->count())->toBe(2);
})->group('Domain');

it('can add group instance to menu', function (): void {
    $group = new DefaultGroup($this->container);
    $group->setName('test');

    $this->menu->addGroup($group);

    expect($this->menu->getGroups())->toBeInstanceOf(Collection::class)
        ->and($this->menu->getGroups()->count())->toBe(1)
        ->and($this->menu->getGroups()->first()->getName())->toBe('test');
})->group('Domain');

it('can add group to menu', function (): void {
    asContainerMock($this->container, 'test', 1);

    $this->menu->group('test');

    expect($this->menu->getGroups())->toBeInstanceOf(Collection::class)
        ->and($this->menu->getGroups()->count())->toBe(1);
})->group('Domain');

it('can add existing group to menu wont duplicate', function (): void {
    asContainerMock($this->container, 'test', 1);

    $this->menu->group('test');
    $this->menu->group('test');
    $this->menu->group('test');

    expect($this->menu->getGroups())->toBeInstanceOf(Collection::class)
        ->and($this->menu->getGroups()->count())->toBe(1);
})->group('Domain');

it('get groups return sorted groups', function (): void {
    $group = new DefaultGroup($this->container);
    $group->setName('second');
    $group->weight(2);

    $this->menu->addGroup($group);

    $group1 = new DefaultGroup($this->container);
    $group1->setName('first');
    $group1->weight(1);

    $this->menu->addGroup($group1);

    expect($this->menu->getGroups())->toBeInstanceOf(Collection::class)
        ->and($this->menu->getGroups()->count())->toBe(2)
        ->and($this->menu->getGroups()->first()->getName())->toBe('first');
})->group('Domain');

it('can combined menu instance', function (): void {
    $group = new DefaultGroup($this->container);
    $group->setName('existing');
    $group->weight(2);
    $this->menu->addGroup($group);

    $menu = new DefaultMenu($this->container);

    $group2 = new DefaultGroup($this->container);
    $group2->setName('new menu group');
    $group2->weight(1);
    $menu->addGroup($group2);

    $this->menu->add($menu);

    expect($this->menu->getGroups())->toBeInstanceOf(Collection::class)
        ->and($this->menu->getGroups()->count())->toBe(2)
        ->and($this->menu->getGroups()->first()->getName())->toBe('new menu group');
})->group('Domain');
