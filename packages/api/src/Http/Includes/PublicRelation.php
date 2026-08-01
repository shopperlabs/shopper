<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Includes;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\QueryBuilder\Includes\IncludeInterface;

abstract class PublicRelation implements IncludeInterface
{
    public function __invoke(Builder $query, string $include): void
    {
        $query->with([$include => static::constraint()]);
    }

    /**
     * The model scope that keeps only the publicly visible rows.
     */
    abstract protected static function scope(): string;

    public static function constraint(): Closure
    {
        $scope = static::scope();

        return function (Relation $relation) use ($scope): void {
            $relation->getQuery()->scopes([$scope]);
        };
    }
}
