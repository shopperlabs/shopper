<?php

declare(strict_types=1);

namespace Shopper\Events;

use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

final class ShopSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $menu->group(__('shopper::layout.sidebar.shop'), function (Group $group): void {
            $group->weight(20);
            $group->setAuthorized();

            $group->item(__('shopper::layout.sidebar.brands'), function (Item $item): void {
                $item->weight(3);
                $item->setAuthorized($this->user->hasPermissionTo('browse_brands'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');
                $item->route('shopper.brands.index');
                $item->setIcon(
                    icon: '
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                        </svg>
                    ',
                    type: 'svg'
                );
            });

            $group->item(__('shopper::layout.sidebar.categories'), function (Item $item): void {
                $item->weight(4);
                $item->setAuthorized($this->user->hasPermissionTo('browse_categories'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');
                $item->route('shopper.categories.index');
                $item->setIcon(icon: 'heroicon-o-tag', iconClass: 'w-5 h-5 mr-2');
            });

            $group->item(__('shopper::layout.sidebar.collections'), function (Item $item): void {
                $item->weight(5);
                $item->setAuthorized($this->user->hasPermissionTo('browse_collections'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');
                $item->route('shopper.collections.index');
                $item->setIcon(
                    icon: '
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 004.5 9v.878m13.5-3A2.25 2.25 0 0119.5 9v.878m0 0a2.246 2.246 0 00-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0121 12v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6c0-.98.626-1.813 1.5-2.122" />
                        </svg>
                    ',
                    type: 'svg'
                );
            });

            $group->item(__('shopper::layout.sidebar.catalog'), function (Item $item): void {
                $item->weight(2);
                $item->setAuthorized($this->user->hasPermissionTo('browse_products'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');
                $item->route('shopper.products.index');
                $item->setIcon(
                    icon: '
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    ',
                    type: 'svg'
                );

                $item->item(__('Attributes'), function (Item $item): void {
                    $item->weight(1);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_products') || $this->user->hasPermissionTo('browse_attributes'));
                    $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                    $item->setActiveClass('text-secondary-900 bg-secondary-100 dark:bg-secondary-700/50 dark:text-white');
                    $item->setInactiveClass('text-secondary-500 dark:text-secondary-400 hover:text-secondary-700 dark:hover:text-secondary-300 hover:bg-secondary-100 dark:hover:bg-secondary-700');
                    $item->route('shopper.attributes.index');
                    $item->setIcon(icon: 'heroicon-o-clipboard-list', iconClass: 'w-5 h-5 mr-2');
                });
            });

            $group->item(__('shopper::layout.sidebar.customers'), function (Item $item): void {
                $item->weight(6);
                $item->setAuthorized($this->user->hasPermissionTo('browse_customers'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');
                $item->route('shopper.customers.index');
                $item->setIcon(
                    icon: '
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    ',
                    type: 'svg'
                );
            });

            $group->item(__('shopper::layout.sidebar.reviews'), function (Item $item): void {
                $item->weight(7);
                $item->setAuthorized($this->user->hasPermissionTo('browse_products'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');
                $item->route('shopper.reviews.index');
                $item->setIcon(
                    icon: '
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                        </svg>
                    ',
                    type: 'svg'
                );
            });
        });

        return $menu;
    }
}