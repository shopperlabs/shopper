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
        $count = Order::query()->where('status', OrderStatus::Pending())->count();

        $menu->group(__('shopper::layout.sidebar.sales'), function (Group $group) use ($count): void {
            $group->weight(3);
            $group->setAuthorized();
            $group->setGroupItemsClass('space-y-1');
            $group->setHeadingClass('sh-heading');

            $group->item(__('shopper::pages/orders.menu'), function (Item $item) use ($count): void {
                $item->weight(1);
                $item->setAuthorized($this->user->hasPermissionTo('browse_orders'));
                $item->setItemClass('sh-sidebar-item group');
                $item->setActiveClass('sh-sidebar-item-active');
                $item->setInactiveClass('sh-sidebar-item-inactive');

                if ($count > 0) {
                    $item->badge($count, 'sh-badge');
                }

                $item->useSpa();
                $item->route('shopper.orders.index');
                $item->setIcon(
                    icon: 'untitledui-shopping-bag',
                    iconClass: 'size-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400'),
                    attributes: [
                        'stroke-width' => '1.5',
                    ],
                );
            });

            if (Feature::enabled('discount')) {
                $group->item(__('shopper::pages/discounts.menu'), function (Item $item): void {
                    $item->weight(2);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_discounts'));
                    $item->setItemClass('sh-sidebar-item group');
                    $item->setActiveClass('sh-sidebar-item-active');
                    $item->setInactiveClass('sh-sidebar-item-inactive');
                    $item->useSpa();
                    $item->route('shopper.discounts.index');
                    $item->setIcon(
                        icon: 'untitledui-sale-03',
                        iconClass: 'size-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400'),
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
