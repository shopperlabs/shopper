<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Infrastructure;

use Illuminate\Support\Collection;
use Shopper\Sidebar\Contracts\Builder\Append;
use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;
use Shopper\Sidebar\Contracts\Sidebar;

final class SidebarSerializer
{
    public function serialize(Sidebar $sidebar): string
    {
        return serialize($sidebar);
    }

    public function unserialize(string $payload): Sidebar
    {
        preg_match_all('/O:\d+:"([^"]+)"/', $payload, $matches);

        $parts = collect([Sidebar::class, Menu::class, Group::class, Item::class, Badge::class, Append::class, Collection::class]);

        /** @var Sidebar $sidebar */
        $sidebar = unserialize($payload, ['allowed_classes' => array_values(array_filter(
            array_unique($matches[1]),
            fn (string $class): bool => $parts->contains(fn (string $part): bool => is_a($class, $part, true)),
        ))]);

        return $sidebar;
    }
}
