<?php

declare(strict_types=1);

namespace Shopper\Events;

use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Models\Order;
use Shopper\Feature;
use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

class SalesSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $count = Order::query()->where('status', OrderStatus::PENDING->value)->count();

        $menu->group(__('shopper::layout.sidebar.sales'), function (Group $group) use ($count): void {
            $group->weight(3);
            $group->setAuthorized(
                Feature::enabled('order') || Feature::enabled('discount')
            );
            $group->setGroupItemsClass('space-y-1');
            $group->setHeadingClass('sh-heading');

            if (Feature::enabled('order')) {
                $group->item(__('shopper::layout.sidebar.orders'), function (Item $item) use ($count): void {
                    $item->weight(1);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_orders'));
                    $item->setItemClass('sh-sidebar-item group');
                    $item->setActiveClass('sh-sidebar-item-active');
                    $item->setInactiveClass('sh-sidebar-item-inactive');

                    if ($count > 0) {
                        $item->badge($count, 'inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20');
                    }

                    $item->useSpa();
                    $item->route('shopper.orders.index');
                    $item->setIcon(
                        icon: 'untitledui-shopping-bag',
                        iconClass: 'mr-3 h-5 w-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400'),
                        attributes: [
                            'stroke-width' => '1.5',
                        ],
                    );
                });
            }

            if (Feature::enabled('discount')) {
                $group->item(__('shopper::layout.sidebar.discounts'), function (Item $item): void {
                    $item->weight(2);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_discounts'));
                    $item->setItemClass('sh-sidebar-item group');
                    $item->setActiveClass('sh-sidebar-item-active');
                    $item->setInactiveClass('sh-sidebar-item-inactive');
                    $item->useSpa();
                    $item->route('shopper.discounts.index');
                    $item->setIcon(
                        icon: 'untitledui-sale-03',
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
