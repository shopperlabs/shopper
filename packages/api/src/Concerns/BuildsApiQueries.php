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
use Shopper\Api\Http\Resources\JsonApiResource;
use Shopper\Api\Support\ResourceManifest;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiValidationException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\Exceptions\InvalidIncludeQuery;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilderRequest;

trait BuildsApiQueries
{
    private const int MAX_FILTER_VALUES = 50;

    /**
     * @template TModel of Model
     *
     * @param  class-string<JsonApiResource>  $resource
     * @param  Builder<TModel>  $query
     * @param  array<int, AllowedFilter>  $extraFilters
     */
    protected function apiQuery(string $resource, Builder $query, array $extraFilters = []): QueryBuilder
    {
        $this->guardFilterBreadth();

        $manifest = app(ResourceManifest::class);
        $resolved = $manifest->for($resource);

        $query->with($manifest->eagerLoadsFor($resource))->with($this->requestedIncludeLoads($resource));

        return QueryBuilder::for($query, $this->requestWithoutSparseFieldsets())
            ->allowedFilters(...$resolved::filters(), ...$extraFilters)
            ->allowedSorts(...$resolved::sorts())
            ->allowedIncludes(...$resolved::includes());
    }

    /**
     * Deep eager-load paths for the includes requested on the current request
     * (e.g. include=variants also needs variants.prices), killing the N+1 that
     * spatie's allowedIncludes would otherwise leave on those nested attributes.
     * Usable from both list (apiQuery) and show endpoints.
     *
     * @param  class-string<JsonApiResource>  $resource
     * @return array<int, string>
     */
    protected function requestedIncludeLoads(string $resource): array
    {
        $map = app(ResourceManifest::class)->for($resource)::includeLoads();

        if ($map === []) {
            return [];
        }

        $loads = [];

        foreach (QueryBuilderRequest::fromRequest(request())->includes() as $include) {
            $loads = array_merge($loads, $map[$include] ?? $map[explode('.', $include, 2)[0]] ?? []);
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
     * @param  class-string<JsonApiResource>  $resource
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    protected function applyPublicIncludes(string $resource, Builder $query): Builder
    {
        $manifest = app(ResourceManifest::class);

        $includes = (new Collection($manifest->for($resource)::includes()))
            ->keyBy(fn (AllowedInclude|string $include): string => $include instanceof AllowedInclude ? $include->getName() : $include);

        $requested = $this->requestedIncludes();
        $unknown = $requested->diff($includes->keys());

        if ($unknown->isNotEmpty()) {
            throw InvalidIncludeQuery::includesNotAllowed($unknown, $includes->keys());
        }

        $query->with($manifest->eagerLoadsFor($resource))->with($this->requestedIncludeLoads($resource));

        $builder = QueryBuilder::for($query, $this->requestWithoutSparseFieldsets());

        foreach ($includes->only($requested->all()) as $include) {
            $include instanceof AllowedInclude ? $include->include($builder) : $query->with($include);
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
     * A `page[cursor]` parameter switches to cursor pagination, whose cost
     * stays flat at any depth when the sort column is indexed: the way to
     * walk a very large catalog.
     *
     * @template TModel of Model
     *
     * @param  class-string<JsonApiResource>  $resource
     * @param  Builder<TModel>  $query
     * @param  array<int, AllowedFilter>  $extraFilters
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

    private function requestWithoutSparseFieldsets(): QueryBuilderRequest
    {
        $request = QueryBuilderRequest::fromRequest(request());
        $request->query->remove(config('query-builder.parameters.fields', 'fields'));

        return $request;
    }

    private function guardFilterBreadth(): void
    {
        QueryBuilderRequest::fromRequest(request())->filters()
            ->each(function (mixed $values, string $filter): void {
                $breadth = (new Collection(Arr::flatten([$values])))
                    ->sum(fn (mixed $value): int => mb_substr_count((string) $value, ',') + 1);

                if ($breadth > self::MAX_FILTER_VALUES) {
                    throw ApiValidationException::withCode(ErrorCode::FilterTooWide, [
                        'filter.'.$filter => __('shopper-api::messages.catalog.filter_too_wide', ['max' => self::MAX_FILTER_VALUES]),
                    ]);
                }
            });
    }
}
