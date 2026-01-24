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
            $group->collapsible();

            $group->item(__('shopper::pages/products.menu'), function (Item $item): void {
                $item->weight(1);
                $item->setAuthorized($this->user->hasPermissionTo('browse_products'));
                $item->useSpa();
                $item->route('shopper.products.index');
                $item->setIcon('phosphor-book-open-text');

                if (Feature::enabled('attribute')) {
                    $item->item(__('shopper::pages/attributes.menu'), function (Item $item): void {
                        $item->weight(1);
                        $item->setAuthorized($this->user->hasPermissionTo('browse_products') || $this->user->hasPermissionTo('browse_attributes'));
                        $item->useSpa();
                        $item->route('shopper.attributes.index');
                    });
                }

                if (Feature::enabled('supplier')) {
                    $item->item(__('shopper::pages/suppliers.menu'), function (Item $item): void {
                        $item->weight(2);
                        $item->setAuthorized($this->user->hasPermissionTo('browse_suppliers'));
                        $item->useSpa();
                        $item->route('shopper.suppliers.index');
                    });
                }
            });

            if (Feature::enabled('category')) {
                $group->item(__('shopper::pages/categories.menu'), function (Item $item): void {
                    $item->weight(2);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_categories'));
                    $item->useSpa();
                    $item->route('shopper.categories.index');
                    $item->setIcon('phosphor-tag');
                });
            }

            if (Feature::enabled('collection')) {
                $group->item(__('shopper::pages/collections.menu'), function (Item $item): void {
                    $item->weight(3);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_collections'));
                    $item->useSpa();
                    $item->route('shopper.collections.index');
                    $item->setIcon('phosphor-stack');
                });
            }

            if (Feature::enabled('brand')) {
                $group->item(__('shopper::pages/brands.menu'), function (Item $item): void {
                    $item->weight(4);
                    $item->setAuthorized($this->user->hasPermissionTo('browse_brands'));
                    $item->useSpa();
                    $item->route('shopper.brands.index');
                    $item->setIcon('phosphor-bookmarks');
                });
            }

        });

        return $menu;
    }
}
