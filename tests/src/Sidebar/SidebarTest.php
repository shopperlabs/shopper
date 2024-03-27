<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Shopper\Sidebar\Contracts\Builder\Menu;
use Shopper\Sidebar\Domain\DefaultGroup;
use Shopper\Sidebar\Domain\DefaultMenu;
use Shopper\Tests\Sidebar\Stubs\SidebarExtenderStub;

beforeEach(function (): void {
    $this->container = Mockery::mock(Container::class);
    $this->menu = new DefaultMenu($this->container);
});

it('a sidebar can be extended with an extender', function (): void {
    $group = new DefaultGroup($this->container);
    $group->setName('demo');
    $this->menu->addGroup($group);

    $extender = new SidebarExtenderStub();
    $extender->extendWith(menu: $this->menu);

    $this->menu->add(
        menu: $extender->extendWith($this->menu)
    );

    expect($this->menu)->toBeInstanceOf(Menu::class)
        ->and($this->menu->getGroups())->toBeInstanceOf(Collection::class)
        ->and($this->menu->getGroups()->count())->toBe(2);
});
