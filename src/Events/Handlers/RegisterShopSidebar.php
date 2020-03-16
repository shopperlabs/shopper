<?php

namespace Shopper\Framework\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Shopper\Framework\Sidebar\AbstractAdminSidebar;

class RegisterShopSidebar extends AbstractAdminSidebar
{
    /**
     * Method used to define your sidebar menu groups and items
     *
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(__('Shop'), function (Group $group) {
            $group->weight(20);
            $group->authorize(true);

            $group->item(__('Categories'), function (Item $item) {
                $item->weight(1);
                $item->authorize(true);
                $item->route('shopper.categories.index');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 transition ease-in-out duration-200" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                ');
            });

            $group->item(__('Brands'), function (Item $item) {
                $item->weight(2);
                $item->authorize(true);
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 transition ease-in-out duration-200" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                ');
            });

            $group->item(__('Products'), function (Item $item) {
                $item->weight(3);
                $item->authorize(true);
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 transition ease-in-out duration-200" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                ');
            });

            $group->item(__('Customers'), function (Item $item) {
                $item->weight(4);
                $item->authorize(true);
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 transition ease-in-out duration-200" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                ');
            });

            $group->item(__('Reviews'), function (Item $item) {
                $item->weight(5);
                $item->authorize(true);
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 transition ease-in-out duration-200" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                ');
            });
        });

        return $menu;
    }
}
