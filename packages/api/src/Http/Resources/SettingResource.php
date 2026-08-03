<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;

class SettingResource extends JsonApiResource
{
    /**
     * The store settings are a singleton, not a row: the id is the constant
     * every client can rely on rather than a primary key.
     */
    public function toId(Request $request): string
    {
        return 'store';
    }

    final public function toType(Request $request): string
    {
        return 'settings';
    }

    public function toAttributes(Request $request): array
    {
        return (array) $this->resource;
    }
}
