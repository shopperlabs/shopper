<?php

declare(strict_types=1);

namespace Shopper\Core\Search;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Category;
use Shopper\Core\Queries\CategoryTree;

class CategoryIndexer extends ScoutIndexer
{
    public function shouldBeSearchable(Model $model): bool
    {
        return resolve(CategoryTree::class)->depth($model->getKey()) !== null;
    }

    /**
     * @return list<string>
     */
    public function getSearchableFields(): array
    {
        return ['name', 'description'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(Model $model): array
    {
        /** @var Category $model */
        return [
            'id' => (string) $this->getScoutKey($model),
            'name' => $model->name,
            'slug' => $model->slug,
            'description' => strip_tags((string) $model->description) ?: null,
            'created_at' => $model->created_at->getTimestamp(),
        ];
    }
}
