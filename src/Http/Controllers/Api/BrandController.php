<?php

namespace Shopper\Framework\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Shopper\Framework\Http\Controllers\ShopperBaseController;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;

class BrandController extends ShopperBaseController
{
    public function __invoke(Request $request): Collection
    {
        return (new BrandRepository())
            ->makeModel()
            ->newQuery()
            ->select('id', 'name')
            ->orderBy('name')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('name', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->selected),
                fn (Builder $query) => $query->limit(10)
            )
            ->get();
    }
}
