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
                $item->icon('menu__link-icon flaticon-map');
            });

            $group->item(__('Products'), function (Item $item) {
                $item->weight(2);
                $item->authorize(true);
                $item->icon('menu__link-icon flaticon-book');
            });

            $group->item(__('Brands'), function (Item $item) {
                $item->weight(3);
                $item->authorize(true);
                $item->icon('menu__link-icon la la-star-o');
            });

            $group->item(__('Reviews'), function (Item $item) {
                $item->weight(4);
                $item->authorize(true);
                $item->icon('menu__link-icon flaticon-customer');
            });

            $group->item(__('Customers'), function (Item $item) {
                $item->weight(5);
                $item->authorize(true);
                $item->icon('menu__link-icon flaticon-users');
            });
        });

        return $menu;
    }
}
