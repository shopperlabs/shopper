<?php

declare(strict_types=1);

namespace Shopper\Core\Search;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Category;

class CategoryIndexer extends ScoutIndexer
{
    public function shouldBeSearchable(Model $model): bool
    {
        /** @var Category $model */
        return $model->is_enabled;
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
            'id' => (string) $model->getKey(),
            'name' => $model->name,
            'slug' => $model->slug,
            'description' => strip_tags((string) $model->description) ?: null,
            'created_at' => $model->created_at->getTimestamp(),
        ];
    }
}
