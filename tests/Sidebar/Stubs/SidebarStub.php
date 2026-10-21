<?php

declare(strict_types=1);

namespace Tests\Sidebar\Stubs;

use Shopper\Sidebar\Contracts\Builder\Append;
use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;
use Shopper\Sidebar\Contracts\Sidebar;

final class SidebarStub implements Sidebar
{
    public function __construct(private Menu $menu) {}

    public function build(): void
    {
        $this->menu->group('Catalog', function (Group $group): void {
            $group->collapsible();
            $group->setClass('catalog-group');
            $group->setGroupItemsClass('catalog-items');
            $group->setHeadingClass('catalog-heading');

            $group->item('Products', function (Item $item): void {
                $item->setUrl('/admin/products');
                $item->setIcon('untitledui-box', iconClass: 'size-5', attributes: ['data-icon' => 'products']);
                $item->isActiveWhen('/admin/products/*');
                $item->isNewTab();
                $item->useSpa();
                $item->setItemClass('products-link');
                $item->setActiveClass('products-active');
                $item->setParentItemClass('products-parent');
                $item->toggleIcon('untitledui-chevron-right', iconClass: 'size-3');
                $item->badge(fn (Badge $badge): Badge => $badge->setValue(3)->color('warning')->setClass('products-badge'));
                $item->append(fn (Append $append): Append => $append->setUrl('/admin/products/create')->setName('Create')->setIcon('untitledui-plus', iconClass: 'size-4')->setClass('products-append'));
                $item->item('Variants', fn (Item $item): Item => $item->setUrl('/admin/variants'));
                $item->item('Archived', function (Item $item): void {
                    $item->setAuthorized(false);
                });
            });

            $reports = new ExternalItem(app());
            $reports->setName('Reports');
            $reports->setUrl('/admin/reports');
            $group->addItem($reports);
        });

        $this->menu->group('Settings', function (Group $group): void {
            $group->setAuthorized(false);
            $group->item('Taxes');
        });
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }
}
