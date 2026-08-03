<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\Includes\IncludeInterface;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilderRequest;

trait BuildsApiQueries
{
    private const int MAX_FILTER_VALUES = 50;

    /**
     * Wrap an Eloquent query with the spatie query builder, applying the
     * filter / sort / include allowlist configured for the given resource.
     * Sparse fieldsets are handled on the response side by the resources.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    protected function apiQuery(string $resource, Builder $query, array $extraFilters = []): QueryBuilder
    {
        $this->guardFilterBreadth();

        $allowlist = (array) config('shopper.api.resources.'.$resource, []);
        $request = QueryBuilderRequest::fromRequest(request());

        $builder = QueryBuilder::for($query, $request)
            ->allowedFilters(...$this->allowedFilters((array) ($allowlist['filters'] ?? [])), ...$extraFilters)
            ->allowedSorts(...$this->allowedSorts((array) ($allowlist['sorts'] ?? [])))
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
     * Install the custom includes configured for a resource on an endpoint that
     * reads a single record. Only a listing goes through the spatie query
     * builder, which is what installs them; a show endpoint resolves its
     * includes straight from the query string, so without this the relations
     * are lazy-loaded at serialization time with no constraint at all.
     *
     * @param  Builder<Model>  $query
     * @return Builder<Model>
     */
    protected function applyPublicIncludes(string $resource, Builder $query): Builder
    {
        $requested = $this->requestedIncludes();

        /** @var array<int|string, string> $includes */
        $includes = (array) config('shopper.api.resources.'.$resource.'.includes', []);

        foreach ($includes as $name => $class) {
            if (is_int($name) || ! $requested->contains($name)) {
                continue;
            }

            $include = resolve($class);

            if ($include instanceof IncludeInterface) {
                $include($query, $name);
            }
        }

        return $query;
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
     * Offset pagination stays the default for classic paged storefronts, with
     * the page number capped so a deep page can never scan the whole table.
     * A `page[cursor]` parameter switches to cursor pagination, whose cost is
     * flat at any depth: the way to walk a very large catalog.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    protected function paginated(string $resource, Builder $query, ?string $defaultSort = null, array $extraFilters = []): CursorPaginator|LengthAwarePaginator
    {
        $default = (int) config('shopper.api.pagination.per_page', 15);
        $max = (int) config('shopper.api.pagination.max_per_page', 100);
        $maxPage = (int) config('shopper.api.pagination.max_page', 100);

        $size = min(max((int) request()->input('page.size', $default), 1), $max);

        $builder = $this->apiQuery($resource, $query, $extraFilters);

        if ($defaultSort !== null) {
            $builder->defaultSort($defaultSort);
        }

        if (request()->has('page.cursor')) {
            $encoded = (string) request()->input('page.cursor');

            return $builder
                ->orderBy($query->qualifyColumn($query->getModel()->getKeyName()))
                ->cursorPaginate(
                    perPage: $size,
                    cursorName: 'page[cursor]',
                    cursor: $encoded !== '' ? Cursor::fromEncoded($encoded) : null,
                )
                ->withQueryString();
        }

        $page = min(max((int) request()->input('page.number', 1), 1), $maxPage);

        return $builder
            ->orderBy($query->qualifyColumn($query->getModel()->getKeyName()))
            ->paginate(perPage: $size, pageName: 'page[number]', page: $page)
            ->withQueryString();
    }

    private function guardFilterBreadth(): void
    {
        QueryBuilderRequest::fromRequest(request())->filters()
            ->each(function (mixed $values, string $filter): void {
                $breadth = (new Collection(Arr::flatten([$values])))
                    ->sum(fn (mixed $value): int => mb_substr_count((string) $value, ',') + 1);

                if ($breadth > self::MAX_FILTER_VALUES) {
                    throw ValidationException::withMessages([
                        'filter.'.$filter => __('shopper-api::messages.catalog.filter_too_wide', ['max' => self::MAX_FILTER_VALUES]),
                    ]);
                }
            });
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
     * Build the spatie sort list from a config descriptor map. A plain string
     * entry sorts by the column of the same name; a `name => ['field', 'alias']`
     * entry sorts by another column or select alias, e.g. the price sort
     * ordering by the `min_price` aggregate.
     *
     * @param  array<int|string, string|array{0: string, 1: string}>  $sorts
     * @return array<int, string|AllowedSort>
     */
    private function allowedSorts(array $sorts): array
    {
        $allowed = [];

        foreach ($sorts as $key => $value) {
            $allowed[] = is_int($key) ? $value : AllowedSort::field($key, $value[1]);
        }

        return $allowed;
    }

    /**
     * Build spatie AllowedFilter instances from a config descriptor map.
     * Each entry is `key => type` where type is 'partial' (default), 'exact',
     * 'scope', or an array carrying the internal name: ['scope', 'internalScopeName']
     * or ['exact', 'relation.column']. A plain string entry (no key) is treated
     * as a partial filter for backward compatibility.
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
                'exact' => AllowedFilter::exact($key, $internal),
                'scope' => $internal !== null ? AllowedFilter::scope($key, $internal) : AllowedFilter::scope($key),
                default => AllowedFilter::partial($key),
            };
        }

        return $allowed;
    }
}
