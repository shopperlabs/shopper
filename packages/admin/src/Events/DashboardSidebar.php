<?php

declare(strict_types=1);

namespace Shopper\Events;

use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

final class DashboardSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $menu->group('', function (Group $group): void {
            $group->weight(1);
            $group->setAuthorized();

            $group->item(__('shopper::pages/dashboard.menu'), function (Item $item): void {
                $item->weight(1);
                $item->setItemClass('sh-sidebar-item group');
                $item->setActiveClass('sh-sidebar-item-active');
                $item->setInactiveClass('sh-sidebar-item-inactive');
                $item->useSpa();
                $item->route('shopper.dashboard');
                $item->setIcon(
                    icon: 'phosphor-monitor',
                    iconClass: 'size-5 '.($item->isActive() ? 'text-primary-500' : 'text-gray-400 dark:text-gray-500'),
                    attributes: [
                        'stroke-width' => '1.5',
                    ]
                );
            });
        });

        return $menu;
    }
}
