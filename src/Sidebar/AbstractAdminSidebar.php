<?php

namespace Shopper\Framework\Sidebar;

use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\SidebarExtender;
use Shopper\Framework\Events\BuildingSidebar;

abstract class AbstractAdminSidebar implements SidebarExtender
{
    /**
     * Logged User.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $user;

    /**
     * @internal param Guard $guard
     */
    public function __construct()
    {
        $this->user = auth(config('shopper.auth.guard'))->user();
    }

    public function handle(BuildingSidebar $sidebar)
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }

    /**
     * Method used to define your sidebar menu groups and items.
     *
     * @return Menu
     */
    abstract public function extendWith(Menu $menu);
}
