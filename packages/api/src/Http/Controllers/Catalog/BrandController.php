<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Concerns\LoadsStock;
use Shopper\Api\Concerns\ResolvesChannel;
use Shopper\Api\Http\Resources\BrandResource;
use Shopper\Core\Models\Contracts\Brand;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class BrandController
{
    use BuildsApiQueries;
    use LoadsStock;
    use ResolvesChannel;

    public function index(): JsonApiResourceCollection
    {
        $query = $this->withMediaIfSupported(resolve(Brand::class)::query()->enabled());

        $brands = $this->paginated('brand', $query);

        $this->loadStockThroughRelation($brands->getCollection());

        return BrandResource::collection($brands);
    }

    public function show(string $slug): JsonApiResource
    {
        $brand = $this->withPublicProducts(
            $this->withMediaIfSupported(resolve(Brand::class)::query()->enabled())
                ->with($this->requestedIncludeLoads('brand'))
        )
            ->where('slug', $slug)
            ->firstOrFail();

        $this->loadStockThroughRelation(collect([$brand]));

        return BrandResource::make($brand);
    }
}
