<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\ProductResource;
use Shopper\Core\Models\Contracts\Product;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class ProductController
{
    use BuildsApiQueries;

    public function index(): JsonApiResourceCollection
    {
        $query = $this->withMediaIfSupported(
            $this->withRatingSummary(resolve(Product::class)::query()->publish()->with('prices.currency'))
        );

        return ProductResource::collection($this->paginated('product', $query));
    }

    public function show(string $slug): JsonApiResource
    {
        $product = $this->withMediaIfSupported(
            $this->withRatingSummary(resolve(Product::class)::query()->publish()->with('prices.currency'))
        )
            ->with($this->requestedIncludeLoads('product'))
            ->where('slug', $slug)
            ->firstOrFail();

        return ProductResource::make($product);
    }

    /**
     * Aggregate the approved-review count and average rating without an N+1,
     * exposed as `reviews_count` and `average_rating` on each product row.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    private function withRatingSummary(Builder $query): Builder
    {
        return $query
            ->withCount(['ratings as reviews_count' => fn (Builder $query) => $query->where('approved', true)])
            ->withAvg(['ratings as average_rating' => fn (Builder $query) => $query->where('approved', true)], 'rating');
    }
}
