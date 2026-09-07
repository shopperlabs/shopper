<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiRequest;
use Illuminate\Http\Resources\JsonApi\JsonApiResource as BaseJsonApiResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Shopper\Api\Support\ResourceManifest;

abstract class JsonApiResource extends BaseJsonApiResource
{
    protected ?JsonApiRequest $includeScope = null;

    /** @var array<string, JsonApiRequest> */
    private array $includeScopes = [];

    public static function make(...$parameters): static
    {
        $resolved = app(ResourceManifest::class)->for(static::class);

        return $resolved === static::class
            ? parent::make(...$parameters)
            : $resolved::make(...$parameters);
    }

    public static function collection(mixed $resource): JsonApiResourceCollection
    {
        return new JsonApiResourceCollection($resource, app(ResourceManifest::class)->for(static::class));
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $included
     * @return array<string, mixed>
     */
    public static function compoundDocumentMembers(Collection $included): array
    {
        return array_filter([
            'included' => $included
                ->uniqueStrict(fn (array $object): string => $object['type'].':'.$object['id'])
                ->map(fn (array $object): array => Arr::except($object, ['_uniqueKey']))
                ->values()
                ->all(),
            ...($implementation = static::$jsonApiInformation)
                ? ['jsonapi' => $implementation]
                : [],
        ]);
    }

    /**
     * The external id is the stable, non-sequential public_id (ULID) rather
     * than the auto-increment primary key. Models without a public_id fall
     * back to their key so the API never breaks.
     */
    public function toId(Request $request): string
    {
        return (string) ($this->resource->public_id ?? $this->resource->getKey());
    }

    public function resolveResourceData(Request $request)
    {
        return parent::resolveResourceData(
            $this->loadedRelationshipsOnly($this->includeScope ?? $this->resolveJsonApiRequestFrom($request))
        );
    }

    public function includePreviouslyLoadedRelationships()
    {
        return $this->includeScope instanceof JsonApiRequest
            ? $this
            : parent::includePreviouslyLoadedRelationships();
    }

    public function with($request): array
    {
        return static::compoundDocumentMembers(
            $this->resolveIncludedResourceObjects($this->resolveJsonApiRequestFrom($request))
        );
    }

    protected function compileIncludedNestedRelationshipsMap(JsonApiRequest $request, Model $relation, BaseJsonApiResource $resource): void
    {
        if ($resource instanceof self) {
            $resource->includeScope = $this->includeScopeFor($request, $this->relationNameOf($relation));
        }
    }

    private function relationNameOf(Model $related): string
    {
        if (! $this->resource instanceof Model) {
            return '';
        }

        foreach ($this->resource->getRelations() as $name => $loaded) {
            foreach ($loaded instanceof Collection ? $loaded : [$loaded] as $model) {
                if ($model === $related) {
                    return $name;
                }
            }
        }

        return '';
    }

    private function loadedRelationshipsOnly(JsonApiRequest $request): JsonApiRequest
    {
        if (! $this->resource instanceof Model) {
            return $request;
        }

        $paths = array_values(array_filter(explode(',', (string) $request->string('include'))));
        $loaded = array_values(array_unique(array_filter(array_map(
            fn (string $path): string => implode('.', $this->loadedPrefix($this->resource, explode('.', $path))),
            $paths,
        ))));

        if ($loaded === $paths) {
            return $request;
        }

        $scoped = JsonApiRequest::createFrom($request);
        $scoped->query->set('include', implode(',', $loaded));

        return $scoped;
    }

    /**
     * @param  array<int, string>  $segments
     * @return array<int, string>
     */
    private function loadedPrefix(mixed $models, array $segments): array
    {
        if ($segments === []) {
            return [];
        }

        $models = Collection::wrap($models)->whereInstanceOf(Model::class);

        if ($models->isEmpty()) {
            return $segments;
        }

        [$segment, $rest] = [$segments[0], array_slice($segments, 1)];

        if ($models->contains(fn (Model $model): bool => ! $model->relationLoaded($segment))) {
            return [];
        }

        $tail = $models
            ->map(fn (Model $model): array => $this->loadedPrefix($model->getRelation($segment), $rest))
            ->sortBy(fn (array $prefix): int => count($prefix))
            ->first();

        return [$segment, ...$tail];
    }

    private function includeScopeFor(JsonApiRequest $request, string $relation): JsonApiRequest
    {
        if (! isset($this->includeScopes[$relation])) {
            $scoped = JsonApiRequest::createFrom($request);
            $scoped->query->set('include', implode(',', $request->sparseIncluded($relation)));

            $this->includeScopes[$relation] = $scoped;
        }

        return $this->includeScopes[$relation];
    }
}
