<?php

namespace Shopper\Framework\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Shopper\Framework\Sidebar\AbstractAdminSidebar;

class RegisterDashboardSidebar extends AbstractAdminSidebar
{
    /**
     * Method used to define your sidebar menu groups and items
     *
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(__('Main'), function (Group $group) {
            $group->weight(1);
            $group->authorize(true);

            $group->item(__('Dashboard'), function (Item $item) {
                $item->weight(1);
                $item->icon('menu__link-icon flaticon2-protection');
                $item->route('shopper.dashboard');
            });
        });

        return $menu;
    }
}
