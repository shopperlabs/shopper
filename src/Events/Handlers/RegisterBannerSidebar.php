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
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 text-gray-500 group-hover:text-gray-500 group-focus:text-gray-600 transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 24 24">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10.217 12.3l1.484 1.483a.412.412 0 010 .6l-4.323 4.322 1.875 1.874c.165.165.247.361.247.587a.8.8 0 01-.247.585.8.8 0 01-.586.248H2.834a.801.801 0 01-.587-.248.8.8 0 01-.247-.585v-5.834c0-.226.083-.421.247-.586a.8.8 0 01.587-.248c.226 0 .421.083.585.248l1.875 1.875 4.323-4.323a.412.412 0 01.599 0v.001zM21.753 2.246a.8.8 0 01.247.586v5.833a.8.8 0 01-.247.586.803.803 0 01-.586.247.802.802 0 01-.586-.247l-1.875-1.875-4.323 4.323a.41.41 0 01-.598 0L12.3 10.215a.412.412 0 010-.598l4.323-4.323-1.875-1.875a.8.8 0 01-.247-.586.8.8 0 01.247-.586.8.8 0 01.586-.247h5.834a.8.8 0 01.585.247z" />
                    </svg>
                ');
            });

            $group->item(__('Slides'), function (Item $item) {
                $item->weight(2);
                $item->authorize(true);
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 text-gray-500 group-hover:text-gray-500 group-focus:text-gray-600 transition ease-in-out duration-150" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                ');
            });
        });

        return $menu;
    }
}
