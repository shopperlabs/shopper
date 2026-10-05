<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Concerns\HandlesPriceQueries;
use Shopper\Api\Concerns\LoadsCalculatedPrices;
use Shopper\Api\Concerns\LoadsPriceRange;
use Shopper\Api\Concerns\LoadsStock;
use Shopper\Api\Concerns\ResolvesChannel;
use Shopper\Api\Concerns\ResolvesCurrency;
use Shopper\Api\Http\Resources\JsonApiResource;
use Shopper\Api\Http\Resources\JsonApiResourceCollection;
use Shopper\Api\Http\Resources\ProductResource;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiValidationException;

final class ProductController
{
    use BuildsApiQueries;
    use HandlesPriceQueries;
    use LoadsCalculatedPrices;
    use LoadsPriceRange;
    use LoadsStock;
    use ResolvesChannel;
    use ResolvesCurrency;

    public function index(): JsonApiResourceCollection
    {
        $currency = $this->resolvedCurrency();

        if ($currency === null && $this->wantsPriceQuery()) {
            throw ApiValidationException::withCode(ErrorCode::CurrencyRequired, [
                $this->wantsPriceSort() ? 'sort' : 'filter.price_min' => __('shopper-api::messages.catalog.no_currency'),
            ]);
        }

        $query = $this->withMediaIfSupported(
            resolve(Product::class)::query()->publish()->with('prices.currency')
        );

        $this->applyChannelScope($query);

        if ($currency !== null && $this->wantsPriceQuery()) {
            $this->applyPriceAggregate($query, $currency->id);
        }

        $range = $this->wantsPriceRange() && $currency !== null
            ? $this->priceRange('product', clone $query, $currency->id)
            : null;

        $products = $this->paginated(
            'product',
            $query,
            extraFilters: $currency !== null ? $this->priceFilters($currency->id) : [],
        );

        $this->loadStockForProducts($products->getCollection());
        $this->loadPriceRangeForProducts($products->getCollection(), $currency?->id);
        $this->loadCalculatedPrices(
            $products->getCollection()->reject(fn (Product $product): bool => $product->canUseVariants()),
            $this->requestPricingContext(),
        );

        return ProductResource::collection($products)
            ->additional(['meta' => [
                'currency' => $currency?->code,
                ...($this->wantsPriceRange()
                    ? ['price_range' => $range === null ? null : [...$range, 'currency_code' => $currency->code]]
                    : []),
            ]]);
    }

    public function show(string $slug): JsonApiResource
    {
        $currency = $this->resolvedCurrency();

        $query = $this->withMediaIfSupported(
            resolve(Product::class)::query()->publish()->with('prices.currency')
        )
            ->with($this->requestedIncludeLoads('product'))
            ->where('slug', $slug);

        $this->applyChannelScope($query);
        $this->applyPublicIncludes('product', $query);

        $product = $query->firstOrFail();

        $this->loadStockForProducts(collect([$product]));
        $this->loadPriceRangeForProducts(collect([$product]), $currency?->id);
        $this->loadCalculatedPrices($this->calculatedPurchasables($product), $this->requestPricingContext());

        return ProductResource::make($product)
            ->additional(['meta' => ['currency' => $currency?->code]]);
    }

    /**
     * @return Collection<int, Model>
     */
    private function calculatedPurchasables(Model $product): Collection
    {
        if (! $product instanceof Product || ! $product->canUseVariants()) {
            return new Collection([$product]);
        }

        return $product->relationLoaded('variants') ? $product->getRelation('variants')->toBase() : new Collection;
    }
}
