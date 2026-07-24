<?php

declare(strict_types=1);

namespace Shopper\Core\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Order;

class OrderIndexer extends ScoutIndexer
{
    /**
     * @param  Builder<covariant Model>  $query
     * @return Builder<covariant Model>
     */
    public function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with('customer');
    }

    /**
     * @return list<string>
     */
    public function getSearchableFields(): array
    {
        return ['number', 'email', 'customer'];
    }

    /**
     * @return list<string>
     */
    public function getFilterableFields(): array
    {
        return ['status'];
    }

    /**
     * @return list<string>
     */
    public function getSortableFields(): array
    {
        return ['created_at', 'total'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(Model $model): array
    {
        /** @var Order $model */
        return [
            'id' => (string) $model->getKey(),
            'number' => $model->number,
            'email' => $model->email,
            'status' => $model->status->value,
            'customer' => $model->customer?->full_name,
            'total' => $model->price_amount,
            'currency_code' => $model->currency_code,
            'created_at' => $model->created_at->getTimestamp(),
        ];
    }
}
