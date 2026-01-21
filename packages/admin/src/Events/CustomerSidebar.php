<?php

declare(strict_types=1);

namespace Shopper\Events;

use Shopper\Feature;
use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

final class CustomerSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $menu->group(__('shopper::pages/customers.menu'), function (Group $group): void {
            $group->weight(3);
            $group->setAuthorized();
            $group->collapsible();

            $group->item(__('shopper::pages/customers.menu'), function (Item $item): void {
                $item->weight(1);
                $item->setAuthorized($this->user->hasPermissionTo('browse_customers'));
                $item->route('shopper.customers.index');
                $item->useSpa();
                $item->setIcon('phosphor-users');
            });

            if (Feature::enabled('review')) {
                $group->item(__('shopper::pages/reviews.menu'), function (Item $item): void {
                    $item->weight(2);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_products'));
                    $item->route('shopper.reviews.index');
                    $item->useSpa();
                    $item->setIcon('phosphor-sparkle');
                });
            }
        });

        return $menu;
    }
}
