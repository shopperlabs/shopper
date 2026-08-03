<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Includes;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Includes\IncludeInterface;

final class ListingPriceRange implements IncludeInterface
{
    public function __invoke(Builder $query, string $include): void {}
}
