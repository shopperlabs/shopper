<?php

declare(strict_types=1);

namespace Shopper\Api\Facades;

use Illuminate\Support\Facades\Facade;
use Shopper\Api\Support\ResourceManifest;

/**
 * @method static void replace(string|array $resource, ?string $replacement = null)
 * @method static string for(string $resource)
 *
 * @see ResourceManifest
 */
final class ApiResource extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ResourceManifest::class;
    }
}
