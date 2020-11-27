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
                $item->authorize($this->user->hasPermissionTo('browse_categories'));
                $item->route('shopper.categories.index');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                ');
            });

            $group->item(__('Brands'), function (Item $item) {
                $item->weight(2);
                $item->authorize($this->user->hasPermissionTo('browse_brands'));
                $item->route('shopper.brands.index');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                ');
            });

            $group->item(__('Collections'), function (Item $item) {
                $item->weight(3);
                $item->authorize($this->user->hasPermissionTo('browse_collections'));
                $item->route('shopper.collections.index');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                ');
            });

            $group->item(__('Catalog'), function (Item $item) {
                $item->weight(4);
                $item->authorize($this->user->hasPermissionTo('browse_products'));
                $item->route('shopper.products.index');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                ');
            });

            $group->item(__('Inventory'), function (Item $item) {
                $item->weight(5);
                $item->authorize(true);
                // $item->route('shopper.inventory-histories.index');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                ');
            });

            $group->item(__('Customers'), function (Item $item) {
                $item->weight(6);
                $item->authorize($this->user->hasPermissionTo('browse_customers'));
                $item->route('shopper.customers.index');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                ');
            });

            $group->item(__('Reviews'), function (Item $item) {
                $item->weight(7);
                $item->authorize($this->user->hasPermissionTo('browse_products'));
                $item->route('shopper.reviews.index');
                $item->icon('
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                ');
            });
        });

        return $menu;
    }
}
