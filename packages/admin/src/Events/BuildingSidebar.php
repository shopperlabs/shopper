<?php

declare(strict_types=1);

namespace Shopper\Framework\Events;

use Maatwebsite\Sidebar\Menu;

class BuildingSidebar
{
    public function __construct(protected Menu $menu)
    {
    }

    public function add(Menu $menu): void
    {
        $this->menu->add($menu);
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }
}
