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
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 transition ease-in-out duration-200" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                ');
            });

            $group->item(__('Shipping'), function (Item $item) {
                $item->weight(7);
                $item->authorize(true);
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 transition ease-in-out duration-200" stroke="none" fill="currentColor" viewBox="0 0 24 24">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M17.765 5.453c2.905 0 5.226 2.405 5.235 5.36v6.042c-.06 1.055-1.028.955-1.028.955v.008c0 1.726-1.431 3.182-3.128 3.182-1.743 0-3.127-1.408-3.127-3.182v-.008h-5.54v.008c0 1.726-1.43 3.182-3.126 3.182-1.73 0-3.106-1.39-3.127-3.143H2.895a.905.905 0 01-.895-.91V2.906A.9.9 0 012.895 2h10.672c2.784.122 2.48 2.881 2.454 3.452h1.744zm0 1.818H16.02v3.726h5.227v-.135c0-2-1.564-3.591-3.483-3.591zM7.046 19.227c.758 0 1.384-.637 1.384-1.409 0-.771-.625-1.408-1.384-1.408-.759 0-1.384.637-1.384 1.408 0 .772.625 1.409 1.384 1.409zm7.236-3.235V4.542a.712.712 0 00-.715-.728H3.743v12.222h.703a3.099 3.099 0 012.6-1.4c1.071 0 2.01.532 2.57 1.356h4.666zm4.554 3.235c.763 0 1.388-.637 1.384-1.409-.043-.77-.625-1.408-1.384-1.408-.759 0-1.384.637-1.384 1.408 0 .772.625 1.409 1.384 1.409zm0-4.59c.977 0 1.842.444 2.412 1.142V12.81H16.02v3.225h.214a3.099 3.099 0 012.6-1.4h.001zm-7.634-7.641c.488 0 .895.41.895.91 0 .501-.403.911-.895.911H6.51a.905.905 0 01-.896-.91c0-.501.403-.911.896-.911h4.69zm0 3.727c.488 0 .89.41.895.91-.047.502-.403.911-.895.911H6.51a.905.905 0 01-.896-.91c0-.501.403-.911.896-.911h4.69z" />
                    </svg>
                ');
            });

            $group->item(__('Marketing'), function (Item $item) {
                $item->weight(8);
                $item->authorize(true);
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5 transition ease-in-out duration-200" stroke="none" fill="currentColor" viewBox="0 0 24 24">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M20.125 19H3.875A1.877 1.877 0 012 17.125V14c0-.345.28-.625.625-.625A1.877 1.877 0 004.5 11.5a1.877 1.877 0 00-1.875-1.875A.625.625 0 012 9V5.875C2 4.841 2.841 4 3.875 4h16.25C21.159 4 22 4.841 22 5.875V9c0 .345-.28.625-.625.625A1.877 1.877 0 0019.5 11.5c0 1.034.841 1.875 1.875 1.875.345 0 .625.28.625.625v3.125A1.877 1.877 0 0120.125 19zM3.25 14.562v2.563c0 .345.28.625.625.625h16.25c.345 0 .625-.28.625-.625v-2.563a3.13 3.13 0 01-2.5-3.062 3.13 3.13 0 012.5-3.062V5.875a.625.625 0 00-.625-.625H3.875a.625.625 0 00-.625.625v2.563a3.13 3.13 0 012.5 3.062 3.13 3.13 0 01-2.5 3.062z" />
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M9.54 16.272l3.749-9.996 1.17.438-3.749 9.997-1.17-.44zM9.043 11.71a1.877 1.877 0 01-1.875-1.875c0-1.034.841-1.875 1.875-1.875s1.875.841 1.875 1.875a1.877 1.877 0 01-1.875 1.875zm0-2.5a.625.625 0 10.001 1.251.625.625 0 000-1.25zM15.504 15.587a1.877 1.877 0 01-1.875-1.875c0-1.034.84-1.875 1.875-1.875 1.034 0 1.875.84 1.875 1.875a1.877 1.877 0 01-1.875 1.875zm0-2.5a.625.625 0 100 1.25.625.625 0 000-1.25z" />
                    </svg>
                ');
            });
        });

        return $menu;
    }
}
