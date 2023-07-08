<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Contracts\Builder;

use Closure;
use Illuminate\Support\Collection;

interface Itemable
{
    public function item(string $name, Closure $callback = null): Item;

    public function addItem(Item $item): Item;

    public function getItems(): Collection;

    public function hasItems(): bool;
}
