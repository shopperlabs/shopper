<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Shopper\Api\Http\Resources\CartResource;
use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\InsufficientStockException;
use Shopper\Cart\Exceptions\MissingPriceException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\Contracts\Cart as CartContract;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\Contracts\ProductVariant as ProductVariantContract;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiException;
use Shopper\Http\Exceptions\ApiValidationException;
use Spatie\QueryBuilder\Exceptions\InvalidIncludeQuery;
use Spatie\QueryBuilder\QueryBuilderRequest;
use Symfony\Component\HttpFoundation\Response;

trait RespondsWithCart
{
    protected function findCart(Request $request, string $publicId): Cart
    {
        /** @var Cart|null $cart */
        $cart = resolve(CartContract::class)::query()->wherePublicId($publicId)->first();

        if (
            ! $cart
            || ($cart->customer_id !== null && $cart->customer_id !== $request->user('sanctum')?->getAuthIdentifier())
        ) {
            throw (new ModelNotFoundException)->setModel(Cart::class, [$publicId]);
        }

        return $cart;
    }

    /**
     * Resolve a cart by its public id without enforcing ownership, so the
     * caller decides how a foreign owner is handled (a guest cart transfer
     * answers 403, not 404, when the cart belongs to another customer).
     */
    protected function findCartOrFail(string $publicId): Cart
    {
        /** @var Cart|null $cart */
        $cart = resolve(CartContract::class)::query()->wherePublicId($publicId)->first();

        if (! $cart) {
            throw (new ModelNotFoundException)->setModel(Cart::class, [$publicId]);
        }

        return $cart;
    }

    /**
     * Every cart response carries the cart totals, so a single GET is enough
     * to render the cart. A fresh cart is served from its persisted rows with
     * zero writes; a mutated or aged cart runs the pipelines once, in which
     * case the reload below picks up the rewritten adjustments and tax lines.
     */
    protected function cartResource(Cart $cart): CartResource
    {
        $includes = $this->cartIncludes();

        $context = resolve(CartManager::class)->totals($cart);

        $cart->load([
            'lines.purchasable' => fn (MorphTo $purchasable) => $purchasable->morphWith($this->purchasableLoads($includes)),
            'lines.adjustments',
            'lines.taxLines',
            'addresses.country',
            'promotions',
            'paymentMethod',
        ]);

        return CartResource::make($cart)->withTotals($context);
    }

    /**
     * Domain failures from the cart manager become HTTP semantics: a stock
     * shortage is a validation error on the quantity, a purchasable without
     * a price in the cart currency a validation error on the cart, touching
     * a completed cart a conflict.
     */
    protected function mutateCart(Closure $operation): mixed
    {
        try {
            return $operation();
        } catch (InsufficientStockException $exception) {
            throw ApiValidationException::withCode(ErrorCode::StockInsufficient, ['quantity' => $exception->getMessage()]);
        } catch (MissingPriceException $exception) {
            throw ApiValidationException::withCode(ErrorCode::PriceMissing, ['cart' => $exception->getMessage()]);
        } catch (CartCompletedException $exception) {
            throw new ApiException(Response::HTTP_CONFLICT, ErrorCode::CartCompleted, $exception->getMessage());
        }
    }

    /**
     * The include paths requested on the current request, checked against the
     * cart allowlist: a cart is served from an already loaded model, so the
     * eager loads are decided here rather than by the spatie query builder.
     *
     * @return Collection<int, string>
     */
    private function cartIncludes(): Collection
    {
        $requested = QueryBuilderRequest::fromRequest(request())->includes();
        $allowed = new Collection((array) config('shopper.api.resources.cart.includes', []));
        $unknown = $requested->diff($allowed);

        if ($unknown->isNotEmpty()) {
            throw InvalidIncludeQuery::includesNotAllowed($unknown, $allowed);
        }

        return $requested;
    }

    /**
     * @param  Collection<int, string>  $includes
     * @return array<class-string, array<int, string>>
     */
    private function purchasableLoads(Collection $includes): array
    {
        if (! $includes->contains(fn (string $include): bool => str_starts_with($include, 'lines.purchasable'))) {
            return [];
        }

        $product = resolve(ProductContract::class);
        $variant = resolve(ProductVariantContract::class);

        $productLoads = $this->serializedLoads($product);
        $variantLoads = $this->serializedLoads($variant);

        if ($includes->contains('lines.purchasable.product')) {
            $variantLoads = [...$variantLoads, ...array_map(fn (string $load): string => 'product.'.$load, $productLoads)];
        }

        return [
            $product::class => $productLoads,
            $variant::class => $variantLoads,
        ];
    }

    /**
     * @return array<int, string>
     */
    private function serializedLoads(object $purchasable): array
    {
        return method_exists($purchasable, 'getMedia') ? ['prices.currency', 'media'] : ['prices.currency'];
    }
}
