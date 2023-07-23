<?php

declare(strict_types=1);

namespace Shopper\Events;

use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

final class CatalogSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $menu->group(__('shopper::layout.sidebar.catalog'), function (Group $group): void {
            $group->weight(2);
            $group->setAuthorized();
            $group->setGroupItemsClass('space-y-1');
            $group->setHeadingClass('menu-heading text-xs leading-5 text-secondary-500 dark:text-secondary-400 uppercase tracking-wider mb-2 font-medium ml-3');

            $group->item(__('shopper::layout.sidebar.products'), function (Item $item): void {
                $item->weight(1);
                $item->setAuthorized($this->user->hasPermissionTo('browse_products'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');
                $item->route('shopper.products.index');
                $item->setIcon(
                    icon: 'untitledui-book-open',
                    iconClass: 'h-5 w-5 mr-2'
                );

                $item->item(__('shopper::words.attributes'), function (Item $item): void {
                    $item->weight(1);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_products') || $this->user->hasPermissionTo('browse_attributes'));
                    $item->setItemClass('group flex items-center py-1 text-sm font-medium');
                    $item->setActiveClass('text-secondary-900 dark:text-white');
                    $item->setInactiveClass('text-secondary-500 dark:text-secondary-400 hover:text-secondary-700 dark:hover:text-secondary-300');
                    $item->route('shopper.attributes.index');
                });
            });
            $group->item(__('shopper::layout.sidebar.categories'), function (Item $item): void {
                $item->weight(2);
                $item->setAuthorized($this->user->hasPermissionTo('browse_categories'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');
                $item->route('shopper.categories.index');
                $item->setIcon(
                    icon: 'untitledui-tag-03',
                    iconClass: 'w-5 h-5 mr-2'
                );
            });
            $group->item(__('shopper::layout.sidebar.collections'), function (Item $item): void {
                $item->weight(3);
                $item->setAuthorized($this->user->hasPermissionTo('browse_collections'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');
                $item->route('shopper.collections.index');
                $item->setIcon(
                    icon: 'untitledui-align-horizontal-centre-02',
                    iconClass: 'h-5 w-5 mr-2'
                );
            });
            $group->item(__('shopper::layout.sidebar.brands'), function (Item $item): void {
                $item->weight(4);
                $item->setAuthorized($this->user->hasPermissionTo('browse_brands'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');
                $item->route('shopper.brands.index');
                $item->setIcon(
                    icon: 'untitledui-bookmark',
                    iconClass: 'h-5 w-5 mr-2'
                );
            });
        });

        return $menu;
    }
}
