<?php

declare(strict_types=1);

namespace Shopper\Events;

use Shopper\Feature;
use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

final class CatalogSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $menu->group(__('shopper::layout.sidebar.catalog'), function (Group $group): void {
            $group->weight(2);
            $group->setAuthorized();
            $group->setGroupItemsClass('space-y-1');
            $group->setHeadingClass('menu-heading text-xs leading-5 text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 font-medium ml-3');

            if (Feature::enabled('product')) {
                $group->item(__('shopper::layout.sidebar.products'), function (Item $item): void {
                    $item->weight(1);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_products'));
                    $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                    $item->setActiveClass('text-primary-600 bg-gray-100 dark:bg-gray-700/50');
                    $item->setInactiveClass('text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-900');
                    $item->useSpa();
                    $item->route('shopper.products.index');
                    $item->setIcon(
                        icon: 'untitledui-book-open',
                        iconClass: 'mr-3 h-5 w-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400'),
                        attributes: [
                            'stroke-width' => '1.5',
                        ],
                    );

                    $item->item(__('shopper::words.attributes'), function (Item $item): void {
                        $item->weight(1);
                        $item->setAuthorized($this->user->hasPermissionTo('browse_products') || $this->user->hasPermissionTo('browse_attributes'));
                        $item->setItemClass('group flex items-center py-1 text-sm font-medium border-l-2 pl-5 -ml-px');
                        $item->setActiveClass('text-gray-700 border-primary-500 dark:text-white');
                        $item->setInactiveClass('text-gray-500 dark:text-gray-400 hover:text-gray-700 border-transparent hover:border-gray-300 dark:hover:border-gray-800 dark:hover:text-gray-300');
                        $item->useSpa();
                        $item->route('shopper.attributes.index');
                    });
                });
            }

            if (Feature::enabled('category')) {
                $group->item(__('shopper::layout.sidebar.categories'), function (Item $item): void {
                    $item->weight(2);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_categories'));
                    $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                    $item->setActiveClass('text-primary-600 bg-gray-100 dark:bg-gray-700/50');
                    $item->setInactiveClass('text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-900');
                    $item->useSpa();
                    $item->route('shopper.categories.index');
                    $item->setIcon(
                        icon: 'untitledui-tag-03',
                        iconClass: 'mr-3 h-5 w-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400'),
                        attributes: [
                            'stroke-width' => '1.5',
                        ],
                    );
                });
            }

            if (Feature::enabled('collection')) {
                $group->item(__('shopper::layout.sidebar.collections'), function (Item $item): void {
                    $item->weight(3);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_collections'));
                    $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                    $item->setActiveClass('text-primary-600 bg-gray-100 dark:bg-gray-700/50');
                    $item->setInactiveClass('text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-900');
                    $item->useSpa();
                    $item->route('shopper.collections.index');
                    $item->setIcon(
                        icon: 'untitledui-align-horizontal-centre-02',
                        iconClass: 'mr-3 h-5 w-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400'),
                        attributes: [
                            'stroke-width' => '1.5',
                        ],
                    );
                });
            }

            if (Feature::enabled('brand')) {
                $group->item(__('shopper::layout.sidebar.brands'), function (Item $item): void {
                    $item->weight(4);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_brands'));
                    $item->setItemClass('group flex items-center rounded-lg py-2 px-3 text-sm font-medium');
                    $item->setActiveClass('text-primary-600 bg-gray-100 dark:bg-gray-700/50');
                    $item->setInactiveClass('text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-900');
                    $item->useSpa();
                    $item->route('shopper.brands.index');
                    $item->setIcon(
                        icon: 'untitledui-bookmark',
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
