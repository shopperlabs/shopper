<?php

declare(strict_types=1);

namespace Shopper\Tests\Sidebar\Stubs;

use Illuminate\Container\Container;
use Mockery;
use Shopper\Sidebar\Contracts\Builder\Menu;
use Shopper\Sidebar\Contracts\SidebarExtender;
use Shopper\Sidebar\Domain\DefaultGroup;

class SidebarExtenderStub implements SidebarExtender
{
    public function extendWith(Menu $menu): Menu
    {
        $container = Mockery::mock(Container::class);

        $group = new DefaultGroup($container);
        $group->setName('new from extender');
        $menu->addGroup($group);

        $group = new DefaultGroup($container);
        $group->setName('demo');
        $menu->addGroup($group);

        return $menu;
    }
}
