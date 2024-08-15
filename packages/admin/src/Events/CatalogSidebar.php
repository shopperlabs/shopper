<?php

declare(strict_types=1);

namespace Shopper\Events;

use Shopper\Feature;
use Shopper\Sidebar\AbstractAdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

class CatalogSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $menu->group(__('shopper::layout.sidebar.catalog'), function (Group $group): void {
            $group->weight(2);
            $group->setAuthorized();
            $group->setGroupItemsClass('space-y-1');
            $group->setHeadingClass('sh-heading');

            $group->item(__('shopper::pages/products.menu'), function (Item $item): void {
                $item->weight(1);
                $item->setAuthorized($this->user->hasPermissionTo('browse_products'));
                $item->setItemClass('sh-sidebar-item group');
                $item->setActiveClass('sh-sidebar-item-active');
                $item->setInactiveClass('sh-sidebar-item-inactive');
                $item->useSpa();
                $item->route('shopper.products.index');
                $item->setIcon(
                    icon: 'untitledui-book-open',
                    iconClass: 'size-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400'),
                    attributes: [
                        'stroke-width' => '1.5',
                    ],
                );

                if (Feature::enabled('attribute')) {
                    $item->item(__('shopper::pages/attributes.menu'), function (Item $item): void {
                        $item->weight(1);
                        $item->setAuthorized($this->user->hasPermissionTo('browse_products') || $this->user->hasPermissionTo('browse_attributes'));
                        $item->setItemClass('sh-sidebar-item-submenu group');
                        $item->setActiveClass('sh-sidebar-item-submenu-active');
                        $item->setInactiveClass('sh-sidebar-item-submenu-inactive');
                        $item->useSpa();
                        $item->route('shopper.attributes.index');
                    });
                }
            });

            if (Feature::enabled('category')) {
                $group->item(__('shopper::pages/categories.menu'), function (Item $item): void {
                    $item->weight(2);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_categories'));
                    $item->setItemClass('sh-sidebar-item group');
                    $item->setActiveClass('sh-sidebar-item-active');
                    $item->setInactiveClass('sh-sidebar-item-inactive');
                    $item->useSpa();
                    $item->route('shopper.categories.index');
                    $item->setIcon(
                        icon: 'untitledui-tag-03',
                        iconClass: 'size-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400'),
                        attributes: [
                            'stroke-width' => '1.5',
                        ],
                    );
                });
            }

            if (Feature::enabled('collection')) {
                $group->item(__('shopper::pages/collections.menu'), function (Item $item): void {
                    $item->weight(3);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_collections'));
                    $item->setItemClass('sh-sidebar-item group');
                    $item->setActiveClass('sh-sidebar-item-active');
                    $item->setInactiveClass('sh-sidebar-item-inactive');
                    $item->useSpa();
                    $item->route('shopper.collections.index');
                    $item->setIcon(
                        icon: 'untitledui-align-horizontal-centre-02',
                        iconClass: 'size-5 ' . ($item->isActive() ? 'text-primary-600' : 'text-gray-400'),
                        attributes: [
                            'stroke-width' => '1.5',
                        ],
                    );
                });
            }

            if (Feature::enabled('brand')) {
                $group->item(__('shopper::pages/brands.menu'), function (Item $item): void {
                    $item->weight(4);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_brands'));
                    $item->setItemClass('sh-sidebar-item group');
                    $item->setActiveClass('sh-sidebar-item-active');
                    $item->setInactiveClass('sh-sidebar-item-inactive');
                    $item->useSpa();
                    $item->route('shopper.brands.index');
                    $item->setIcon(
                        icon: 'untitledui-bookmark',
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
