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
                $item->setAuthorized($this->user->hasPermissionTo('products.browse'));
                $item->useSpa();
                $item->route('shopper.products.index');
                $item->setIcon('phosphor-package');

                if (Feature::enabled('attribute')) {
                    $item->item(__('shopper::pages/attributes.menu'), function (Item $item): void {
                        $item->weight(1);
                        $item->setAuthorized($this->user->hasPermissionTo('products.browse') || $this->user->hasPermissionTo('attributes.browse'));
                        $item->useSpa();
                        $item->route('shopper.attributes.index');
                    });
                }

                if (Feature::enabled('supplier')) {
                    $item->item(__('shopper::pages/suppliers.menu'), function (Item $item): void {
                        $item->weight(2);
                        $item->setAuthorized($this->user->hasPermissionTo('suppliers.browse'));
                        $item->useSpa();
                        $item->route('shopper.suppliers.index');
                    });
                }

                if (Feature::enabled('tag')) {
                    $item->item(__('shopper::pages/tags.menu'), function (Item $item): void {
                        $item->weight(3);
                        $item->setAuthorized($this->user->hasPermissionTo('products.browse') || $this->user->hasPermissionTo('tags.browse'));
                        $item->useSpa();
                        $item->route('shopper.tags.index');
                    });
                }
            });

            if (Feature::enabled('category')) {
                $group->item(__('shopper::pages/categories.menu'), function (Item $item): void {
                    $item->weight(2);
                    $item->setAuthorized($this->user->hasPermissionTo('categories.browse'));
                    $item->useSpa();
                    $item->route('shopper.categories.index');
                    $item->setIcon('phosphor-tag');
                });
            }

            if (Feature::enabled('collection')) {
                $group->item(__('shopper::pages/collections.menu'), function (Item $item): void {
                    $item->weight(3);
                    $item->setAuthorized($this->user->hasPermissionTo('collections.browse'));
                    $item->useSpa();
                    $item->route('shopper.collections.index');
                    $item->setIcon('phosphor-stack');
                });
            }

            if (Feature::enabled('brand')) {
                $group->item(__('shopper::pages/brands.menu'), function (Item $item): void {
                    $item->weight(4);
                    $item->setAuthorized($this->user->hasPermissionTo('brands.browse'));
                    $item->useSpa();
                    $item->route('shopper.brands.index');
                    $item->setIcon('phosphor-bookmarks');
                });
            }

        });

        return $menu;
    }
}
