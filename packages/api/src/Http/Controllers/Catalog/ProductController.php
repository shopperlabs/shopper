<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\ProductResource;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class ProductController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        $query = resolve(ProductContract::class)::query()
            ->publish()
            ->with('prices.currency');

        return ProductResource::collection($this->paginated('product', $this->withMediaIfSupported($query)));
    }

    public function show(string $slug): JsonApiResource
    {
        $product = $this->withMediaIfSupported(
            resolve(ProductContract::class)::query()->publish()->with('prices.currency')
        )
            ->with($this->requestedIncludeLoads('product'))
            ->where('slug', $slug)
            ->firstOrFail();

        return ProductResource::make($product);
    }
}
