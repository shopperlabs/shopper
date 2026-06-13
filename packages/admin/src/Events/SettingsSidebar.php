<?php

declare(strict_types=1);

namespace Shopper\Events;

use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

final class SettingsSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $menu->group(__('shopper::pages/settings/global.menu'), function (Group $group): void {
            $group->weight(10);
            $group->setAuthorized($this->user->hasPermissionTo('system.settings'));
            $group->collapsible();

            $group->item(__('shopper::pages/settings/global.site'), function (Item $item): void {
                $item->weight(1);
                $item->setAuthorized($this->user->hasPermissionTo('system.settings'));
                $item->route('shopper.settings.shop');
                $item->useSpa();
                $item->setIcon('phosphor-faders');
            });
        });

        return $menu;
    }
}
