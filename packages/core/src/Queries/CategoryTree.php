<?php

declare(strict_types=1);

namespace Shopper\Core\Queries;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Shopper\Core\Models\Contracts\Category;

final class CategoryTree
{
    private const string VERSION_KEY = 'shopper.categories.version';

    /**
     * @var array{depth: array<int|string, int>, children: array<int|string, array<int, int|string>>, hidden: array<int, int|string>}|null
     */
    private ?array $memo = null;

    public static function flush(): void
    {
        Cache::add(self::VERSION_KEY, 0);
        Cache::increment(self::VERSION_KEY);

        if (app()->resolved(self::class)) {
            resolve(self::class)->memo = null;
        }
    }

    public function depth(int|string $id): ?int
    {
        return $this->tree()['depth'][$id] ?? null;
    }

    /**
     * @return array<int, int|string>
     */
    public function hiddenIds(): array
    {
        return $this->tree()['hidden'];
    }

    /**
     * @return array<int, int|string>
     */
    public function subtreeIds(int|string $id): array
    {
        $tree = $this->tree();

        if (! array_key_exists($id, $tree['depth'])) {
            return [];
        }

        $ids = [$id];

        for ($index = 0; $index < count($ids); $index++) {
            foreach ($tree['children'][$ids[$index]] ?? [] as $child) {
                $ids[] = $child;
            }
        }

        return $ids;
    }

    /**
     * @return array{depth: array<int|string, int>, children: array<int|string, array<int, int|string>>, hidden: array<int, int|string>}
     */
    private function tree(): array
    {
        if ($this->memo !== null) {
            return $this->memo;
        }

        $ttl = config('shopper.core.cache.category_tree');

        if (blank($ttl)) {
            return $this->memo = $this->build();
        }

        $version = (int) Cache::get(self::VERSION_KEY, 0);

        /** @var array{depth: array<int|string, int>, children: array<int|string, array<int, int|string>>, hidden: array<int, int|string>} $tree */
        $tree = Cache::flexible(
            'shopper.category-tree.'.$version,
            [(int) $ttl, (int) $ttl * 24],
            fn (): array => $this->build(),
        );

        return $this->memo = $tree;
    }

    /**
     * @return array{depth: array<int|string, int>, children: array<int|string, array<int, int|string>>, hidden: array<int, int|string>}
     */
    private function build(): array
    {
        /** @var Collection<\Shopper\Core\Models\Category> $rows */
        $rows = resolve(Category::class)::query()
            ->toBase()
            ->get(['id', 'parent_id', 'is_enabled']);

        $children = [];
        $queue = [];

        foreach ($rows as $row) {
            if (! $row->is_enabled) {
                continue;
            }

            if (blank($row->parent_id)) {
                $queue[] = [$row->id, 0];

                continue;
            }

            $children[$row->parent_id][] = $row->id;
        }

        $depth = [];

        for ($index = 0; $index < count($queue); $index++) {
            [$id, $level] = $queue[$index];

            $depth[$id] = $level;

            foreach ($children[$id] ?? [] as $child) {
                $queue[] = [$child, $level + 1];
            }
        }

        $hidden = [];

        foreach ($rows as $row) {
            if (! array_key_exists($row->id, $depth)) {
                $hidden[] = $row->id;
            }
        }

        return ['depth' => $depth, 'children' => $children, 'hidden' => $hidden];
    }
}
