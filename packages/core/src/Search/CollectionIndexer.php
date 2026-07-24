<?php

declare(strict_types=1);

namespace Shopper\Core\Search;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Collection;

class CollectionIndexer extends ScoutIndexer
{
    public function shouldBeSearchable(Model $model): bool
    {
        /** @var Collection $model */
        return $model->published_at->isPast();
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
        /** @var Collection $model */
        return [
            'id' => (string) $model->getKey(),
            'name' => $model->name,
            'slug' => $model->slug,
            'description' => strip_tags((string) $model->description) ?: null,
            'published_at' => $model->published_at->getTimestamp(),
            'created_at' => $model->created_at->getTimestamp(),
        ];
    }
}
