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

            $group->item(__('shopper::layout.sidebar.dashboard'), function (Item $item): void {
                $item->weight(1);
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-gray-100 dark:bg-gray-700/50');
                $item->setInactiveClass('text-gray-600 dark:text-gray-300 hover:text-gray-900 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-900');
                $item->useSpa();
                $item->route('shopper.dashboard');
                $item->setIcon(
                    icon: 'untitledui-home-line',
                    iconClass: 'mr-3 h-5 w-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400'),
                    attributes: [
                        'stroke-width' => '1.5',
                    ]
                );
            });
        });

        return $menu;
    }
}
