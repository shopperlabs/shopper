<?php

namespace Shopper\Framework\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Shopper\Framework\Sidebar\AbstractAdminSidebar;

class RegisterDashboardSidebar extends AbstractAdminSidebar
{
    /**
     * Method used to define your sidebar menu groups and items.
     */
    public function extendWith(Menu $menu): Menu
    {
        $menu->group(__(''), function (Group $group) {
            $group->weight(1);
            $group->authorize(true);

            $group->item(__('Dashboard'), function (Item $item) {
                $item->weight(1);
                $item->route('shopper.dashboard');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                ');
            });
        });

        return $menu;
    }
}
