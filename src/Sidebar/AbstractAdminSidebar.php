<?php

namespace Shopper\Framework\Sidebar;

use Illuminate\Contracts\Auth\Guard;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\SidebarExtender;
use Shopper\Framework\Events\BuildingSidebar;

abstract class AbstractAdminSidebar implements SidebarExtender
{
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * @internal param Guard $guard
     */
    public function __construct()
    {
        $this->auth = auth();
    }

    /**
     * @param BuildingSidebar $sidebar
     */
    public function handle(BuildingSidebar $sidebar)
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }

    /**
     * Method used to define your sidebar menu groups and items
     *
     * @param Menu $menu
     * @return Menu
     */
    abstract public function extendWith(Menu $menu);
}
