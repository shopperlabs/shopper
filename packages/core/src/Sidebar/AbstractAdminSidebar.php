<?php

declare(strict_types=1);

namespace Shopper\Core\Sidebar;

use Illuminate\Contracts\Auth\Authenticatable;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\SidebarExtender;
use Shopper\Core\Events\BuildingSidebar;

abstract class AbstractAdminSidebar implements SidebarExtender
{
    protected ?Authenticatable $user = null;

    public function __construct()
    {
        $this->user = auth(config('shopper.auth.guard'))->user();
    }

    public function handle(BuildingSidebar $sidebar): void
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }

    /**
     * Method used to define your sidebar menu groups and items.
     */
    abstract public function extendWith(Menu $menu): Menu;
}
