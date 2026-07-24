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
            'name' => mb_trim(implode(' ', array_filter([$model->getAttribute('first_name'), $model->getAttribute('last_name')]))) ?: null,
            'email' => $model->getAttribute('email'),
            'created_at' => $model->getAttribute('created_at')?->getTimestamp(),
        ];
    }
}
