<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Includes;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Includes\IncludeInterface;

final class RatingAggregate implements IncludeInterface
{
    public function __invoke(Builder $query, string $include): void
    {
        $query
            ->withCount(['ratings as reviews_count' => fn (Builder $query) => $query->where('approved', true)])
            ->withAvg(['ratings as average_rating' => fn (Builder $query) => $query->where('approved', true)], 'rating');
    }
}
