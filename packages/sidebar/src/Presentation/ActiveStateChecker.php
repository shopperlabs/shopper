<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation;

use Illuminate\Support\Facades\Request;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;

final class ActiveStateChecker
{
    public function isActive(Item $item): bool
    {
        foreach ($item->getItems() as $child) {
            if ($this->isActive($child)) {
                return true;
            }
        }

        if ($path = $item->getActiveWhen()) {
            return Request::is($path);
        }

        $path = mb_ltrim(str_replace(url('/'), '', $item->getUrl()), '/');

        return Request::is(
            $path,
            $path.'/*'
        );
    }

    public function findActivePath(Menu $menu): ?ActivePath
    {
        foreach ($menu->getGroups() as $group) {
            if (! $group->isAuthorized()) {
                continue;
            }

            foreach ($group->getItems() as $item) {
                if (! $item->isAuthorized()) {
                    continue;
                }

                if (! $item->isActive()) {
                    continue;
                }

                return new ActivePath(
                    group: $group,
                    items: $this->chainToLeaf($item),
                );
            }
        }

        return null;
    }

    /**
     * @return non-empty-list<Item>
     */
    protected function chainToLeaf(Item $item): array
    {
        foreach ($item->getItems() as $child) {
            if (! $child->isAuthorized()) {
                continue;
            }

            if ($child->isActive()) {
                return [$item, ...$this->chainToLeaf($child)];
            }
        }

        return [$item];
    }
}
