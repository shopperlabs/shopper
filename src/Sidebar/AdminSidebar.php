<?php

namespace Shopper\Framework\Sidebar;

use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\ShouldCache;
use Maatwebsite\Sidebar\Sidebar;
use Maatwebsite\Sidebar\Traits\CacheableTrait;
use Shopper\Framework\Events\BuildingSidebar;

class AdminSidebar implements ShouldCache, Sidebar
{
    use CacheableTrait;

    /**
     * @var Menu
     */
    protected $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Build your sidebar implementation here.
     */
    public function build()
    {
        event($event = new BuildingSidebar($this->menu));
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }
}
