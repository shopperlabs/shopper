<?php

namespace Shopper\Framework\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Shopper\Framework\Sidebar\AbstractAdminSidebar;

class RegisterOrderSidebar extends AbstractAdminSidebar
{
    /**
     * Method used to define your sidebar menu groups and items
     *
     * @param  Menu  $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(__('Shop'), function (Group $group) {
            $group->weight(20);
            $group->authorize(true);

            $group->item(__('Orders'), function (Item $item) {
                $item->weight(1);
                $item->authorize($this->user->hasPermissionTo('browse_orders'));
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                ');
            });

            $group->item(__('Discounts'), function (Item $item) {
                $item->weight(9);
                $item->authorize($this->user->hasPermissionTo('browse_discounts'));
                $item->route('shopper.discounts.index');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" stroke="none" fill="currentColor" viewBox="0 0 24 24">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M20.125 19H3.875A1.877 1.877 0 012 17.125V14c0-.345.28-.625.625-.625A1.877 1.877 0 004.5 11.5a1.877 1.877 0 00-1.875-1.875A.625.625 0 012 9V5.875C2 4.841 2.841 4 3.875 4h16.25C21.159 4 22 4.841 22 5.875V9c0 .345-.28.625-.625.625A1.877 1.877 0 0019.5 11.5c0 1.034.841 1.875 1.875 1.875.345 0 .625.28.625.625v3.125A1.877 1.877 0 0120.125 19zM3.25 14.562v2.563c0 .345.28.625.625.625h16.25c.345 0 .625-.28.625-.625v-2.563a3.13 3.13 0 01-2.5-3.062 3.13 3.13 0 012.5-3.062V5.875a.625.625 0 00-.625-.625H3.875a.625.625 0 00-.625.625v2.563a3.13 3.13 0 012.5 3.062 3.13 3.13 0 01-2.5 3.062z" />
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M9.54 16.272l3.749-9.996 1.17.438-3.749 9.997-1.17-.44zM9.043 11.71a1.877 1.877 0 01-1.875-1.875c0-1.034.841-1.875 1.875-1.875s1.875.841 1.875 1.875a1.877 1.877 0 01-1.875 1.875zm0-2.5a.625.625 0 10.001 1.251.625.625 0 000-1.25zM15.504 15.587a1.877 1.877 0 01-1.875-1.875c0-1.034.84-1.875 1.875-1.875 1.034 0 1.875.84 1.875 1.875a1.877 1.877 0 01-1.875 1.875zm0-2.5a.625.625 0 100 1.25.625.625 0 000-1.25z" />
                    </svg>
                ');
            });
        });

        return $menu;
    }
}
