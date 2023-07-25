<?php

namespace Shopper\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

trait HasCollectionPaginate
{
    public function paginate($items, $perPage = 15, $page = null, $options = []): LengthAwarePaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator(
            items: $items->forPage($page, $perPage),
            total: $items->count(),
            perPage: $perPage,
            currentPage: $page,
            options: $options
        );
    }
}
