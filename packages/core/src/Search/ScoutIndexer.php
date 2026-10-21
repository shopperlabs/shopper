<?php

declare(strict_types=1);

namespace Shopper\Core\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Traits\HasPublicId;

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
        return $model->getAttribute($this->getScoutKeyName($model));
    }

    public function getScoutKeyName(Model $model): string
    {
        return in_array(HasPublicId::class, class_uses_recursive($model), true)
            ? 'public_id'
            : $model->getKeyName();
    }

    public function getScoutKeyType(Model $model): string
    {
        return $this->getScoutKeyName($model) === 'public_id' ? 'string' : $model->getKeyType();
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
            $model->toArray(),
            ['id' => (string) $this->getScoutKey($model)],
            array_filter([
                'created_at' => $model->getAttribute('created_at')?->getTimestamp(),
                'updated_at' => $model->getAttribute('updated_at')?->getTimestamp(),
            ]),
        );
    }
}
