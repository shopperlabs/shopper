<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Includes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Spatie\QueryBuilder\Includes\IncludeInterface;

final class EnabledAncestors implements IncludeInterface
{
    public function __invoke(Builder $query, string $include): void
    {
        $query->with([$include => function (Relation $ancestors): void {
            $ancestors->getQuery()->scopes(['enabled'])->orderBy('depth');
        }]);
    }
}
