<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilderRequest;

trait BuildsApiQueries
{
    /**
     * Wrap an Eloquent query with the spatie query builder, applying the
     * filter / sort / include allowlist configured for the given resource.
     * Sparse fieldsets are handled on the response side by the resources.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    protected function apiQuery(string $resource, Builder $query): QueryBuilder
    {
        $allowlist = (array) config('shopper.api.resources.'.$resource, []);
        $request = QueryBuilderRequest::fromRequest(request());

        $builder = QueryBuilder::for($query, $request)
            ->allowedFilters(...($allowlist['filters'] ?? []))
            ->allowedSorts(...($allowlist['sorts'] ?? []))
            ->allowedIncludes(...($allowlist['includes'] ?? []));

        $loads = $this->requestedIncludeLoads($resource);

        if ($loads !== []) {
            $builder->with($loads);
        }

        return $builder;
    }

    /**
     * Deep eager-load paths for the includes requested on the current request
     * (e.g. include=variants also needs variants.prices), killing the N+1 that
     * spatie's allowedIncludes would otherwise leave on those nested attributes.
     * Usable from both list (apiQuery) and show endpoints.
     *
     * @return array<int, string>
     */
    protected function requestedIncludeLoads(string $resource): array
    {
        /** @var array<string, array<int, string>> $map */
        $map = (array) config('shopper.api.resources.'.$resource.'.include_loads', []);

        if ($map === []) {
            return [];
        }

        $loads = [];

        foreach (QueryBuilderRequest::fromRequest(request())->includes() as $include) {
            $loads = array_merge($loads, $map[$include] ?? []);
        }

        return array_values(array_unique($loads));
    }

    /**
     * Eager-load the media relation only when the model supports it (the
     * default models do; a swapped core-only model may not).
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected function withMediaIfSupported(Builder $query): Builder
    {
        if (method_exists($query->getModel(), 'getMedia')) {
            $query->with('media');
        }

        return $query;
    }

    /**
     * Apply the resource allowlist then paginate with the configured page size.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    protected function paginated(string $resource, Builder $query): LengthAwarePaginator
    {
        $default = (int) config('shopper.api.pagination.per_page', 15);
        $max = (int) config('shopper.api.pagination.max_per_page', 100);

        $size = min(max((int) request()->input('page.size', $default), 1), $max);
        $page = max((int) request()->input('page.number', 1), 1);

        return $this->apiQuery($resource, $query)
            ->paginate(perPage: $size, pageName: 'page[number]', page: $page)
            ->withQueryString();
    }
}
