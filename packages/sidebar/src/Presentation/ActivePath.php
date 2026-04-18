<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation;

use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;

final class ActivePath
{
    /**
     * @param  non-empty-list<Item>  $items
     */
    public function __construct(
        public readonly Group $group,
        public readonly array $items,
    ) {}

    public function topLevel(): Item
    {
        return $this->items[0];
    }

    public function leaf(): Item
    {
        return $this->items[count($this->items) - 1];
    }
}
