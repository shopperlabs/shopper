<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\Includes\IncludeInterface;
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
            ->allowedFilters(...$this->allowedFilters((array) ($allowlist['filters'] ?? [])))
            ->allowedSorts(...($allowlist['sorts'] ?? []))
            ->allowedIncludes(...$this->allowedIncludes((array) ($allowlist['includes'] ?? [])));

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
     * The include names requested on the current request, for endpoints that
     * apply opt-in behavior (e.g. aggregates) outside the spatie query builder.
     *
     * @return Collection<int, string>
     */
    protected function requestedIncludes(): Collection
    {
        return QueryBuilderRequest::fromRequest(request())->includes();
    }

    /**
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

    /**
     * Build the spatie include list from a config descriptor map. A plain
     * string entry is a relationship include; a `name => class-string` entry
     * is a custom include (IncludeInterface) applied to the query on demand,
     * e.g. the opt-in rating aggregates.
     *
     * @param  array<int|string, string>  $includes
     * @return array<int, string|AllowedInclude>
     */
    private function allowedIncludes(array $includes): array
    {
        $allowed = [];

        foreach ($includes as $key => $value) {
            if (is_int($key)) {
                $allowed[] = $value;

                continue;
            }

            /** @var IncludeInterface $include */
            $include = resolve($value);

            $allowed[] = AllowedInclude::custom($key, $include);
        }

        return $allowed;
    }

    /**
     * Build spatie AllowedFilter instances from a config descriptor map.
     * Each entry is `key => type` where type is 'partial' (default), 'exact',
     * 'scope', or ['scope', 'internalScopeName']. A plain string entry (no key)
     * is treated as a partial filter for backward compatibility.
     *
     * @param  array<int|string, string|array{0: string, 1?: string}>  $filters
     * @return array<int, AllowedFilter>
     */
    private function allowedFilters(array $filters): array
    {
        $allowed = [];

        foreach ($filters as $key => $type) {
            if (is_int($key)) {
                $allowed[] = AllowedFilter::partial((string) $type);

                continue;
            }

            [$kind, $internal] = is_array($type) ? [$type[0], $type[1] ?? null] : [$type, null];

            $allowed[] = match ($kind) {
                'exact' => AllowedFilter::exact($key),
                'scope' => $internal !== null ? AllowedFilter::scope($key, $internal) : AllowedFilter::scope($key),
                default => AllowedFilter::partial($key),
            };
        }

        return $allowed;
    }
}
