<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Concerns\LoadsStock;
use Shopper\Api\Http\Includes\RatingAggregate;
use Shopper\Api\Http\Resources\ProductResource;
use Shopper\Core\Models\Contracts\Product;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class ProductController
{
    use BuildsApiQueries;
    use LoadsStock;

    public function index(): JsonApiResourceCollection
    {
        $query = $this->withMediaIfSupported(
            resolve(Product::class)::query()->publish()->with('prices.currency')
        );

        $products = $this->paginated('product', $query);

        $this->loadStockForProducts($products->getCollection());

        return ProductResource::collection($products);
    }

    public function show(string $slug): JsonApiResource
    {
        $query = $this->withMediaIfSupported(
            resolve(Product::class)::query()->publish()->with('prices.currency')
        )
            ->with($this->requestedIncludeLoads('product'))
            ->where('slug', $slug);

        if ($this->requestedIncludes()->contains('rating')) {
            (new RatingAggregate)($query, 'rating');
        }

        $product = $query->firstOrFail();

        $this->loadStockForProducts(collect([$product]));

        return ProductResource::make($product);
    }
}
