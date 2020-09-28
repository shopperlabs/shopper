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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10M9 21h6" />
                    </svg>
                ');
            });
        });

        return $menu;
    }
}
