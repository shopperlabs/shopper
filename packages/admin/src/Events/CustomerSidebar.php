<?php

declare(strict_types=1);

namespace Shopper\Events;

use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

class CustomerSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $menu->group(__('shopper::layout.sidebar.customers'), function (Group $group): void {
            $group->weight(3);
            $group->setAuthorized();
            $group->setGroupItemsClass('space-y-1');
            $group->setHeadingClass('menu-heading text-xs leading-5 text-secondary-500 dark:text-secondary-400 uppercase tracking-wider mb-2 font-medium ml-3');

            $group->item(__('shopper::layout.sidebar.customers'), function (Item $item): void {
                $item->weight(1);
                $item->setAuthorized($this->user->hasPermissionTo('browse_customers'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-600 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-50 dark:hover:bg-gray-900');
                $item->route('shopper.customers.index');
                $item->setIcon(
                    icon: 'untitledui-users-02',
                    iconClass: 'mr-3 h-5 w-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-secondary-400'),
                    attributes: [
                        'stroke-width' => '1.5',
                    ],
                );
            });
            $group->item(__('shopper::layout.sidebar.reviews'), function (Item $item): void {
                $item->weight(2);
                $item->setAuthorized($this->user->hasPermissionTo('browse_products'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-600 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-50 dark:hover:bg-gray-900');
                $item->route('shopper.reviews.index');
                $item->setIcon(
                    icon: 'untitledui-message-heart-square',
                    iconClass: 'mr-3 h-5 w-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-secondary-400'),
                    attributes: [
                        'stroke-width' => '1.5',
                    ],
                );
            });
        });

        return $menu;
    }
}
