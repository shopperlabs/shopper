<?php

namespace Shopper\Framework\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Shopper\Framework\Sidebar\AbstractAdminSidebar;

class RegisterBannerSidebar extends AbstractAdminSidebar
{
    /**
     * Method used to define your sidebar menu groups and items
     *
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(__('Banners'), function (Group $group) {
            $group->weight(30);
            $group->authorize(true);

            $group->item(__('Sizes'), function (Item $item) {
                $item->weight(1);
                $item->authorize(true);
                $item->icon('menu__link-icon flaticon2-size');
            });

            $group->item(__('Slides'), function (Item $item) {
                $item->weight(2);
                $item->authorize(true);
                $item->icon('menu__link-icon flaticon2-photograph');
            });
        });

        return $menu;
    }
}
