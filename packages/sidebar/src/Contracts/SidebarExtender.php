<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Contracts;

use Shopper\Sidebar\Contracts\Builder\Menu;

interface SidebarExtender
{
    public function extendWith(Menu $menu): Menu;
}
