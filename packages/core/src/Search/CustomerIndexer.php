<?php

declare(strict_types=1);

namespace Shopper\Core\Search;

use Illuminate\Database\Eloquent\Model;

class CustomerIndexer extends ScoutIndexer
{
    /**
     * @return list<string>
     */
    public function getSearchableFields(): array
    {
        return ['name', 'email'];
    }

    /** @return array<string, mixed> */
    public function toSearchableArray(Model $model): array
    {
        return [
            'id' => (string) $model->getKey(),
            'name' => mb_trim(implode(' ', array_filter([$model->first_name, $model->last_name]))) ?: null,
            'email' => $model->email,
            'created_at' => $model->created_at->getTimestamp(),
        ];
    }
}
