<?php

declare(strict_types=1);

namespace Shopper\Api\Support;

use InvalidArgumentException;
use Shopper\Api\Http\Resources\JsonApiResource;

final class ResourceManifest
{
    /** @var array<class-string<JsonApiResource>, class-string<JsonApiResource>> */
    private array $replacements = [];

    /** @var array<class-string<JsonApiResource>, array<int, string>> */
    private array $eagerLoads = [];

    /**
     * @param  class-string<JsonApiResource>|array<class-string<JsonApiResource>, class-string<JsonApiResource>>  $resource
     * @param  class-string<JsonApiResource>|null  $replacement
     */
    public function replace(string|array $resource, ?string $replacement = null): void
    {
        $replacements = is_array($resource) ? $resource : [$resource => $replacement];

        foreach ($replacements as $stock => $custom) {
            if (! is_string($custom) || ! is_a($custom, $stock, true)) {
                throw new InvalidArgumentException(sprintf(
                    'The resource [%s] must extend [%s] to replace it.',
                    is_string($custom) ? $custom : get_debug_type($custom),
                    $stock,
                ));
            }
        }

        $this->replacements = [...$this->replacements, ...$replacements];
    }

    /**
     * @template TResource of JsonApiResource
     *
     * @param  class-string<TResource>  $resource
     * @return class-string<TResource>
     */
    public function for(string $resource): string
    {
        return $this->replacements[$resource] ?? $resource;
    }

    /**
     * @param  class-string<JsonApiResource>  $resource
     * @param  array<int, string>  $relations
     */
    public function eagerLoad(string $resource, array $relations): void
    {
        $this->eagerLoads[$resource] = [...$this->eagerLoads[$resource] ?? [], ...$relations];
    }

    /**
     * @param  class-string<JsonApiResource>  $resource
     * @return array<int, string>
     */
    public function eagerLoadsFor(string $resource): array
    {
        return array_values(array_unique([
            ...$this->for($resource)::eagerLoads(),
            ...$this->eagerLoads[$resource] ?? [],
        ]));
    }
}
