<?php

declare(strict_types=1);

namespace Shopper\Framework\Sidebar\Presentation;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Sidebar\Presentation\SidebarRenderer;
use Maatwebsite\Sidebar\Sidebar;

class ShopperSidebarRenderer implements SidebarRenderer
{
    protected string $view = 'shopper::sidebar.menu';

    public function __construct(protected Factory $factory)
    {
    }

    public function render(Sidebar $sidebar): ?View
    {
        $menu = $sidebar->getMenu();

        if (! $menu->isAuthorized()) {
            return null;
        }

        $groups = [];
        foreach ($menu->getGroups() as $group) {
            $groups[] = (new ShopperGroupRenderer($this->factory))->render($group);
        }

        return $this->factory->make($this->view, [
            'groups' => $groups,
        ]);
    }
}
