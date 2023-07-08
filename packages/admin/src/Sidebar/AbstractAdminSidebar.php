<?php

namespace Shopper\Sidebar;

use Illuminate\Contracts\Auth\Authenticatable;
use Shopper\Facades\Shopper;
use Shopper\Sidebar\Contracts\SidebarExtender;
use Shopper\Sidebar\Contracts\Builder\Menu;

abstract class AbstractAdminSidebar implements SidebarExtender
{
    protected ?Authenticatable $user;

    public function __construct()
    {
        $this->user = Shopper::auth()->user();
    }

    public function handle(SidebarBuilder $sidebar): void
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }

    abstract public function extendWith(Menu $menu): Menu;
}
