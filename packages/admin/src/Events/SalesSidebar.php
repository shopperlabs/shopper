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

final class SalesSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $count = Order::query()->where('status', OrderStatus::New)->count();

        $menu->group(__('shopper::layout.sidebar.sales'), function (Group $group) use ($count): void {
            $group->weight(3);
            $group->setAuthorized();
            $group->collapsible();

            $group->item(__('shopper::pages/orders.menu'), function (Item $item) use ($count): void {
                $item->weight(1);
                $item->setAuthorized($this->user->hasPermissionTo('browse_orders'));

                if ($count > 0) {
                    $item->badge($count)->color('warning');
                }

                $item->useSpa();
                $item->route('shopper.orders.index');
                $item->setIcon('phosphor-shopping-bag');

                $item->item(__('shopper::pages/orders.shipments'), function (Item $item): void {
                    $item->weight(1);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_orders'));
                    $item->useSpa();
                    $item->route('shopper.orders.shipments');
                });

                $item->item(__('shopper::pages/orders.abandoned_carts.menu'), function (Item $item): void {
                    $item->weight(2);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_orders'));
                    $item->useSpa();
                    $item->route('shopper.orders.abandoned-carts');
                });
            });

            if (Feature::enabled('discount')) {
                $group->item(__('shopper::pages/discounts.menu'), function (Item $item): void {
                    $item->weight(2);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_discounts'));
                    $item->useSpa();
                    $item->route('shopper.discounts.index');
                    $item->setIcon('phosphor-seal-percent');
                });
            }
        });

        return $menu;
    }
}
