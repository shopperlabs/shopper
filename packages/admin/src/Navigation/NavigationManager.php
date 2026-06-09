<?php

declare(strict_types=1);

namespace Shopper\Navigation;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Shopper\Contracts\NavigationGroup;
use Shopper\Contracts\NavigationItem;

/**
 * @template TItem of NavigationItem
 */
abstract class NavigationManager
{
    /** @var array<class-string<TItem>, bool> */
    protected array $items = [];

    abstract protected function resolveGroup(string $value): ?NavigationGroup;

    /**
     * @param  array<class-string<TItem>, bool>  $items
     */
    public function register(array $items): static
    {
        $this->items = array_merge($this->items, $items);

        return $this;
    }

    /**
     * @param  class-string<TItem>  $item
     */
    public function add(string $item, bool $enabled = true): static
    {
        $this->items[$item] = $enabled;

        return $this;
    }

    /**
     * @param  class-string<TItem>  $item
     */
    public function enable(string $item): static
    {
        if (array_key_exists($item, $this->items)) {
            $this->items[$item] = true;
        }

        return $this;
    }

    /**
     * @param  class-string<TItem>  $item
     */
    public function disable(string $item): static
    {
        if (array_key_exists($item, $this->items)) {
            $this->items[$item] = false;
        }

        return $this;
    }

    /**
     * @param  (callable(TItem): bool)|null  $filter
     * @return Collection<int, TItem>
     */
    public function all(?callable $filter = null): Collection
    {
        /** @var Collection<int, TItem> $items */
        $items = collect($this->items)
            ->filter(fn (bool $enabled): bool => $enabled)
            ->keys()
            ->map(fn (string $class): NavigationItem => app($class))
            ->sortBy(fn (NavigationItem $item): int => $item->order())
            ->values();

        if ($filter !== null) {
            /** @var Collection<int, TItem> $items */
            $items = $items->filter($filter)->values();
        }

        return $items;
    }

    /**
     * @param  (callable(TItem): bool)|null  $filter
     * @return Collection<int, array{group: ?string, label: ?string, items: Collection<int, TItem>}>
     */
    public function grouped(?callable $filter = null): Collection
    {
        $items = $this->all($filter);

        /** @var Collection<int, array{group: ?string, label: ?string, items: Collection<int, TItem>}> $buckets */
        $buckets = collect();

        $groupValues = $items
            ->map(fn (NavigationItem $item): ?string => $item->group())
            ->filter(fn (?string $group): bool => $group !== null)
            ->unique()
            ->values();

        $known = $groupValues
            ->filter(fn (string $group): bool => $this->resolveGroup($group) instanceof NavigationGroup)
            ->sortBy(function (string $group): int {
                $resolved = $this->resolveGroup($group);

                return $resolved instanceof NavigationGroup ? $resolved->order() : 0;
            })
            ->values();

        foreach ($known as $group) {
            $resolved = $this->resolveGroup($group);

            if (! $resolved instanceof NavigationGroup) {
                continue;
            }

            $buckets->push([
                'group' => $group,
                'label' => $resolved->getLabel(),
                'items' => $items->filter(fn (NavigationItem $item): bool => $item->group() === $group)->values(),
            ]);
        }

        $custom = $groupValues
            ->filter(fn (string $group): bool => $this->resolveGroup($group) === null)
            ->sort()
            ->values();

        foreach ($custom as $group) {
            $buckets->push([
                'group' => $group,
                'label' => Str::headline($group),
                'items' => $items->filter(fn (NavigationItem $item): bool => $item->group() === $group)->values(),
            ]);
        }

        $ungrouped = $items
            ->filter(fn (NavigationItem $item): bool => $item->group() === null)
            ->values();

        if ($ungrouped->isNotEmpty()) {
            $buckets->push([
                'group' => null,
                'label' => null,
                'items' => $ungrouped,
            ]);
        }

        /** @phpstan-ignore return.type */
        return $buckets;
    }

    /**
     * @return array<class-string<TItem>, bool>
     */
    public function registered(): array
    {
        return $this->items;
    }
}
