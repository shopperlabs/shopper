<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Api\Support\ResourceManifest;
use TiMacDonald\JsonApi\JsonApiResource as BaseJsonApiResource;

/**
 * Base class every Shopper store resource extends. It carries the JSON:API
 * serialization from timacdonald/json-api and gives the package a single
 * place to add cross-resource conventions as the API grows.
 *
 * Concrete resources declare their shape with the `$attributes` /
 * `$relationships` properties or by overriding `toAttributes()` /
 * `toRelationships()`.
 *
 * A resource registered through ApiResource::replace() is resolved here, so
 * every call site (controllers, nested relationships, traits) serializes with
 * the replacement without being aware of it.
 */
abstract class JsonApiResource extends BaseJsonApiResource
{
    public static function make(...$parameters)
    {
        $resolved = app(ResourceManifest::class)->for(static::class);

        return $resolved === static::class
            ? parent::make(...$parameters)
            : $resolved::make(...$parameters);
    }

    public static function collection(mixed $resource)
    {
        $resolved = app(ResourceManifest::class)->for(static::class);

        return $resolved === static::class
            ? parent::collection($resource)
            : $resolved::collection($resource);
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
}
