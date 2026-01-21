<?php

declare(strict_types=1);

namespace Shopper\Events;

use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

final class DashboardSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $menu->group(function (Group $group): void {
            $group->weight(1);
            $group->setAuthorized();

            $group->item(__('shopper::pages/dashboard.menu'), function (Item $item): void {
                $item->weight(1);
                $item->route('shopper.dashboard');
                $item->useSpa();
                $item->setIcon('phosphor-monitor');
            });
        });

        return $menu;
    }
}
