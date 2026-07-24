<?php

declare(strict_types=1);

namespace Shopper\Core\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ScoutIndexer
{
    public function searchableAs(Model $model): string
    {
        $name = str_replace(
            (string) config('shopper.core.table_prefix'),
            '',
            $model->getTable()
        );

        return config('scout.prefix').$name;
    }

    public function shouldBeSearchable(Model $model): bool
    {
        return true;
    }

    /**
     * @param  Builder<covariant Model>  $query
     * @return Builder<covariant Model>
     */
    public function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query;
    }

    public function getScoutKey(Model $model): mixed
    {
        return $model->getKey();
    }

    public function getScoutKeyName(Model $model): string
    {
        return $model->getKeyName();
    }

    /**
     * @return list<string>
     */
    public function getSearchableFields(): array
    {
        return ['name'];
    }

    /**
     * @return list<string>
     */
    public function getSortableFields(): array
    {
        return ['created_at', 'updated_at'];
    }

    /**
     * @return list<string>
     */
    public function getFilterableFields(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(Model $model): array
    {
        return array_merge(
            ['id' => (string) $model->getKey()],
            $model->toArray(),
            array_filter([
                'created_at' => $model->created_at?->getTimestamp(),
                'updated_at' => $model->updated_at?->getTimestamp(),
            ]),
        );
    }
}
