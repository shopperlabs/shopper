<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Includes;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Shopper\Core\Queries\CategoryTree;
use Spatie\QueryBuilder\Includes\IncludeInterface;

final class VisibleCategories implements IncludeInterface
{
    public function __invoke(Builder $query, string $include): void
    {
        $query->with([$include => self::constraint()]);
    }

    public static function constraint(): Closure
    {
        return function (Relation $categories): void {
            $hidden = resolve(CategoryTree::class)->hiddenIds();

            $categories->getQuery()->when(
                $hidden !== [],
                fn (Builder $query) => $query->whereKeyNot($hidden)
            );
        };
    }
}
