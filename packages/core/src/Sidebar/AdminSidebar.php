<?php

declare(strict_types=1);

namespace Shopper\Core\Sidebar;

use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\ShouldCache;
use Maatwebsite\Sidebar\Sidebar;
use Maatwebsite\Sidebar\Traits\CacheableTrait;
use Shopper\Core\Events\BuildingSidebar;

class AdminSidebar implements ShouldCache, Sidebar
{
    use CacheableTrait;

    public function __construct(protected Menu $menu)
    {
    }

    public function build(): void
    {
        event(new BuildingSidebar($this->menu));
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }
}
