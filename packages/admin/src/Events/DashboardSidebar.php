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
                $item->route('shopper.dashboard');
                $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                $item->setActiveClass('text-primary-600 bg-secondary-100 dark:bg-secondary-700/50');
                $item->setInactiveClass('text-secondary-700 dark:text-secondary-300 hover:text-secondary-900 dark:hover:text-white hover:bg-secondary-100 dark:hover:bg-secondary-700');
                $item->setIcon(
                    icon: '
                        <svg class="h-5 w-5 mr-2" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 17h8M11.018 2.764 4.235 8.039c-.453.353-.68.53-.843.75a2 2 0 0 0-.318.65C3 9.704 3 9.991 3 10.565V17.8c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874C4.52 21 5.08 21 6.2 21h11.6c1.12 0 1.68 0 2.108-.218a2 2 0 0 0 .874-.874C21 19.48 21 18.92 21 17.8v-7.235c0-.574 0-.861-.074-1.126a2.002 2.002 0 0 0-.318-.65c-.163-.22-.39-.397-.843-.75l-6.783-5.275c-.351-.273-.527-.41-.72-.462a1 1 0 0 0-.523 0c-.194.052-.37.189-.721.462Z"/>
                        </svg>
                    ',
                    type: 'svg'
                );
            });
        });

        return $menu;
    }
}
