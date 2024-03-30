<?php

declare(strict_types=1);

namespace Shopper\Events;

use Shopper\Feature;
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
            $group->setHeadingClass('menu-heading text-xs leading-5 text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 font-medium ml-3');

            if (Feature::enabled('customer')) {
                $group->item(__('shopper::layout.sidebar.customers'), function (Item $item): void {
                    $item->weight(1);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_customers'));
                    $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                    $item->setActiveClass('text-primary-600 bg-gray-100 dark:bg-gray-700/50');
                    $item->setInactiveClass('text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-900');
                    $item->route('shopper.customers.index');
                    $item->useSpa();
                    $item->setIcon(
                        icon: 'untitledui-users-02',
                        iconClass: 'mr-3 h-5 w-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400'),
                        attributes: [
                            'stroke-width' => '1.5',
                        ],
                    );
                });
            }

            if (Feature::enabled('review')) {
                $group->item(__('shopper::layout.sidebar.reviews'), function (Item $item): void {
                    $item->weight(2);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_products'));
                    $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                    $item->setActiveClass('text-primary-600 bg-gray-100 dark:bg-gray-700/50');
                    $item->setInactiveClass('text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-900');
                    $item->route('shopper.reviews.index');
                    $item->useSpa();
                    $item->setIcon(
                        icon: 'untitledui-message-heart-square',
                        iconClass: 'mr-3 h-5 w-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400'),
                        attributes: [
                            'stroke-width' => '1.5',
                        ],
                    );
                });
            }
        });

        return $menu;
    }
}
