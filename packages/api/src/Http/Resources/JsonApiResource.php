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

    public function with($request): array
    {
        return static::compoundDocumentMembers(
            $this->resolveIncludedResourceObjects($this->resolveJsonApiRequestFrom($request))
        );
    }

    /**
     * A resource only exposes the relationships named on its own include
     * path, and only those the endpoint already loaded: relations loaded
     * for other purposes stay out of the document, and nothing is lazy
     * loaded while serializing.
     */
    protected function requestedResourceRelationships(JsonApiRequest $request, ?string $relationName = null): array
    {
        if (! $this->resource instanceof Model) {
            return [];
        }

        if ($relationName !== null) {
            return $this->requestedRelationships === null
                ? $request->sparseIncluded($relationName) ?? []
                : $this->requestedRelationshipsBelow($relationName);
        }

        $relations = $this->requestedRelationships === null
            ? $request->sparseIncluded() ?? []
            : array_map(fn (string $path): string => explode('.', $path, 2)[0], $this->requestedRelationships);

        return array_values(array_unique(array_filter(
            $relations,
            fn (string $relation): bool => $this->resource->relationLoaded($relation),
        )));
    }

    /**
     * @return array<int, string>
     */
    private function requestedRelationshipsBelow(string $relationName): array
    {
        return (new Collection($this->requestedRelationships))
            ->filter(fn (string $path): bool => str_starts_with($path, $relationName.'.'))
            ->map(fn (string $path): string => mb_substr($path, mb_strlen($relationName) + 1))
            ->values()
            ->all();
    }
}
