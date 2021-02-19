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
        $menu->group(__(''), function (Group $group) {
            $group->weight(1);
            $group->authorize(true);

            $group->item(__('Dashboard'), function (Item $item) {
                $item->weight(1);
                $item->route('shopper.dashboard');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 transition ease-in-out duration-200" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                ');
            });

            $group->item(__('Analytics'), function (Item $item) {
                $item->weight(1);
                $item->route('shopper.analytics');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 transition ease-in-out duration-200" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                ');
            });
        });

        return $menu;
    }
}
