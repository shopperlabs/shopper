<?php

declare(strict_types=1);

namespace Shopper\Events;

use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Models\Order;
use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

final class SalesSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $count = Order::query()->where('status', OrderStatus::PENDING->value)->count();

        $menu->group(__('shopper::layout.sidebar.sales'), function (Group $group) use ($count): void {
            $group->weight(3);
            $group->setAuthorized();
            $group->setGroupItemsClass('space-y-1');
            $group->setHeadingClass('menu-heading text-xs leading-5 text-secondary-500 dark:text-secondary-400 uppercase tracking-wider mb-2 font-medium ml-3');

            $group->item(__('shopper::layout.sidebar.orders'), function (Item $item) use ($count): void {
                $item->weight(1);
                $item->setAuthorized($this->user->hasPermissionTo('browse_orders'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-500 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');

                if ($count > 0) {
                    $item->badge($count, 'inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20');
                }

                $item->route('shopper.orders.index');
                $item->setIcon(
                    icon: 'untitledui-shopping-bag',
                    iconClass: 'h-5 w-5 mr-2'
                );
            });
            $group->item(__('shopper::layout.sidebar.discounts'), function (Item $item): void {
                $item->weight(2);
                $item->setAuthorized($this->user->hasPermissionTo('browse_discounts'));
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-500 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');
                $item->route('shopper.discounts.index');
                $item->setIcon(
                    icon: 'untitledui-sale-03',
                    iconClass: 'h-5 w-5 mr-2'
                );
            });
        });

        return $menu;
    }
}
