<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Shopper\Sidebar\Contracts\Sidebar;
use Shopper\Sidebar\Presentation\SidebarRenderer as SidebarRendererContract;

final class ShopperSidebarRenderer implements SidebarRendererContract
{
    protected string $view = 'shopper::sidebar.menu';

    public function __construct(protected Factory $factory)
    {
    }

    public function render(Sidebar $sidebar): ?View
    {
        $menu = $sidebar->getMenu();

        if ($menu->isAuthorized()) {
            $groups = [];
            foreach ($menu->getGroups() as $group) {
                $groups[] = (new ShopperGroupRenderer($this->factory))->render($group);
            }

            return $this->factory->make($this->view, [
                'groups' => $groups,
            ]);
        }

        return null;
    }
}
