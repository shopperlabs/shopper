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
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(__('Shop'), function (Group $group) {
            $group->weight(20);
            $group->authorize(true);

            $group->item(__('Orders'), function (Item $item) {
                $item->weight(6);
                $item->authorize(true);
                $item->icon('menu__link-icon flaticon2-shopping-cart-1');
            });

            $group->item(__('Shipping'), function (Item $item) {
                $item->weight(7);
                $item->authorize(true);
                $item->icon('menu__link-icon flaticon2-delivery-truck');
            });

            $group->item(__('Marketing'), function (Item $item) {
                $item->weight(8);
                $item->icon('menu__link-icon flaticon-interface-9');
                $item->toggleIcon('menu__ver-arrow la la-angle-right');
                $item->authorize(true);

                $item->item(__('Coupon'), function (Item $item) {
                    $item->weight(1);
                    $item->authorize(true);
                    $item->icon('menu__link-bullet menu__link-bullet--dot');
                    $item->append();
                });
            });
        });

        return $menu;
    }
}
