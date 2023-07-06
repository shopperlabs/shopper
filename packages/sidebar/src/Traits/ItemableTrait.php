<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Traits;

use Closure;
use Illuminate\Support\Collection;
use Shopper\Sidebar\Contracts\Builder\Item;

trait ItemableTrait
{
    protected Collection $items;

    public function item(string $name, Closure $callback = null): self
    {
        if ($this->items->has($name)) {
            $item = $this->items->get($name);
        } else {
            $item = $this->container->make(Item::class);
            $item->setName($name);
        }

        $this->call($callback, $item);

        $this->addItem($item);

        return $item;
    }

    public function addItem(Item $item): self
    {
        $this->items->put($item->getName(), $item);

        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items->sortBy(fn (Item $item) => $item->getWeight());
    }

    public function hasItems(): bool
    {
        return count($this->items) > 0;
    }
}
