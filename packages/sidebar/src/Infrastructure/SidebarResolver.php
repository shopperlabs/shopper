<?php

namespace Shopper\Sidebar\Infrastructure;

use Shopper\Sidebar\Contracts\Sidebar;

interface SidebarResolver
{
    public function resolve(string $name): Sidebar;
}
