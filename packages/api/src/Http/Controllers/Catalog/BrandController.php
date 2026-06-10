<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\BrandResource;
use Shopper\Core\Models\Contracts\Brand;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class BrandController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        $query = $this->withMediaIfSupported(resolve(Brand::class)::query()->enabled());

        return BrandResource::collection($this->paginated('brand', $query));
    }

    public function show(string $slug): JsonApiResource
    {
        $brand = $this->withMediaIfSupported(resolve(Brand::class)::query()->enabled())
            ->with($this->requestedIncludeLoads('brand'))
            ->where('slug', $slug)
            ->firstOrFail();

        return BrandResource::make($brand);
    }
}
