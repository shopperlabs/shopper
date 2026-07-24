<?php

declare(strict_types=1);

namespace Shopper\Core\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Product;

class ProductIndexer extends ScoutIndexer
{
    public function shouldBeSearchable(Model $model): bool
    {
        /** @var Product $model */
        return $model->is_visible
            && $model->published_at !== null
            && $model->published_at->isPast();
    }

    /**
     * @param  Builder<covariant Model>  $query
     * @return Builder<covariant Model>
     */
    public function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with(['brand', 'categories', 'variants']);
    }

    /** @return list<string> */
    public function getSearchableFields(): array
    {
        return ['name', 'sku', 'skus', 'description', 'brand', 'categories'];
    }

    /** @return list<string> */
    public function getSortableFields(): array
    {
        return ['created_at', 'published_at'];
    }

    /** @return list<string> */
    public function getFilterableFields(): array
    {
        return ['brand', 'categories', 'type'];
    }

    /** @return array<string, mixed> */
    public function toSearchableArray(Model $model): array
    {
        /** @var Product $model */
        return [
            'id' => (string) $model->getKey(),
            'name' => $model->name,
            'slug' => $model->slug,
            'sku' => $model->sku,
            'skus' => $model->variants->pluck('sku')->filter()->values()->all(),
            'description' => strip_tags((string) $model->description),
            'summary' => strip_tags((string) $model->summary) ?: null,
            'type' => $model->type?->value,
            'brand' => $model->brand?->name,
            'categories' => $model->categories->pluck('name')->all(),
            'published_at' => $model->published_at?->getTimestamp(),
            'created_at' => $model->created_at->getTimestamp(),
        ];
    }
}
