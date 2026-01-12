<?php

declare(strict_types=1);

namespace Shopper\Sidebar;

use Illuminate\Contracts\Auth\Authenticatable;
use Shopper\Sidebar\Contracts\Builder\Menu;
use Shopper\Sidebar\Contracts\SidebarExtender;

abstract class AbstractAdminSidebar implements SidebarExtender
{
    protected ?Authenticatable $user;

    public function __construct()
    {
        $this->user = auth()->user(); // @phpstan-ignore-line
    }

    abstract public function extendWith(Menu $menu): Menu;

    public function handle(SidebarBuilder $sidebar): void
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }
}
