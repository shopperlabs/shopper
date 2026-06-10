<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use TiMacDonald\JsonApi\JsonApiResource as BaseJsonApiResource;

/**
 * Base class every Shopper store resource extends. It carries the JSON:API
 * serialization from timacdonald/json-api and gives the package a single
 * place to add cross-resource conventions as the API grows.
 *
 * Concrete resources declare their shape with the `$attributes` /
 * `$relationships` properties or by overriding `toAttributes()` /
 * `toRelationships()`.
 */
abstract class JsonApiResource extends BaseJsonApiResource
{
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
