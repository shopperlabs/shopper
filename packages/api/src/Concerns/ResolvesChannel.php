<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shopper\Api\Http\Includes\PublicProducts;
use Shopper\Core\Models\Contracts\Channel;

trait ResolvesChannel
{
    /**
     * Restrict the catalog to the sales channel resolved for the request,
     * when one is. The channel is a soft context: without one the catalog
     * stays unfiltered, mirroring how the zone drives pricing.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected function applyChannelScope(Builder $query): Builder
    {
        $channel = $this->resolvedChannel();

        if ($channel !== null) {
            $query->scopes(['channel' => [[$channel->id]]]);
        }

        return $query;
    }

    /**
     * Eager-load a products relation through the public catalog constraints on
     * the endpoints that read a single record, where no query builder installs
     * the custom include. Without it the relation is lazy-loaded at
     * serialization time, unfiltered.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected function withPublicProducts(Builder $query, string $relation = 'products'): Builder
    {
        if (! $this->requestedIncludes()->contains($relation)) {
            return $query;
        }

        return $query->with([$relation => PublicProducts::constraint()]);
    }

    protected function resolvedChannel(): ?Channel
    {
        $channel = request()->attributes->get('shopper_channel');

        return $channel instanceof Channel ? $channel : null;
    }
}
