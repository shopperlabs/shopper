<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Includes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Shopper\Core\Models\Contracts\Category as CategoryContract;
use Shopper\Core\Models\Contracts\Channel;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Spatie\QueryBuilder\Includes\IncludeInterface;

final class SubtreeProductsCount implements IncludeInterface
{
    public function __invoke(Builder $query, string $include): void {}

    /**
     * @param  Collection<int, Model>  $categories
     */
    public static function load(Collection $categories): void
    {
        if ($categories->isEmpty()) {
            return;
        }

        $roots = $categories->filter(fn (Model $category): bool => (bool) $category->getAttribute('is_enabled'));

        $counts = $roots->isEmpty() ? [] : self::counts($roots);

        $categories->each(function (Model $category) use ($counts): void {
            $category->setAttribute('products_count', (int) ($counts[$category->getKey()] ?? 0));
        });
    }

    /**
     * @param  Collection<int, Model>  $roots
     * @return array<int|string, int>
     */
    private static function counts(Collection $roots): array
    {
        $pivot = shopper_table('product_has_relations');
        $products = resolve(ProductContract::class)::query()->getModel()->getTable();
        $morphClass = $roots->first()->getMorphClass();
        $channel = request()->attributes->get('shopper_channel');

        $selects = [];
        $bindings = [];
        $keys = [];
        $subtreeIds = [];

        foreach ($roots as $root) {
            if (! $root instanceof CategoryContract) {
                continue;
            }

            $ids = $root->enabledSubtreeIds()->values()->all();

            if ($ids === []) {
                continue;
            }

            $alias = 'c'.count($keys);
            $keys[$alias] = $root->getKey();
            $placeholders = implode(', ', array_fill(0, count($ids), '?'));
            $selects[] = "COUNT(DISTINCT CASE WHEN {$pivot}.productable_id IN ({$placeholders}) THEN {$pivot}.product_id END) AS {$alias}";
            $bindings = [...$bindings, ...$ids];
            $subtreeIds = [...$subtreeIds, ...$ids];
        }

        if ($selects === []) {
            return [];
        }

        $row = resolve(ProductContract::class)::query()
            ->scopes(['publish'])
            ->when($channel instanceof Channel, fn (Builder $query) => $query->scopes(['channel' => [[$channel->id]]]))
            ->join($pivot, $pivot.'.product_id', '=', $products.'.id')
            ->where($pivot.'.productable_type', $morphClass)
            ->whereIn($pivot.'.productable_id', array_values(array_unique($subtreeIds)))
            ->toBase()
            ->selectRaw(implode(', ', $selects), $bindings)
            ->first();

        $counts = [];

        foreach ($keys as $alias => $key) {
            $counts[$key] = (int) ($row->{$alias} ?? 0);
        }

        return $counts;
    }
}
