<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Includes;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Shopper\Core\Models\Contracts\Channel;
use Spatie\QueryBuilder\Includes\IncludeInterface;

final class PublicProducts implements IncludeInterface
{
    public function __invoke(Builder $query, string $include): void
    {
        $query->with([$include => self::constraint()]);
    }

    /**
     * Published, restricted to the sales channel resolved for the request, and
     * carrying the prices the resources serialize.
     */
    public static function constraint(): Closure
    {
        return function (Relation $products): void {
            $query = $products->getQuery()->scopes(['publish'])->with('prices.currency');

            $channel = request()->attributes->get('shopper_channel');

            if ($channel instanceof Channel) {
                $query->scopes(['channel' => [[$channel->id]]]);
            }
        };
    }
}
