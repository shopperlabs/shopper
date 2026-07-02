<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Shopper\Api\Http\Resources\CartResource;
use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\InsufficientStockException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\Contracts\Cart as CartContract;
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
        $context = resolve(CartManager::class)->totals($cart);

        $cart->load(['lines.purchasable', 'lines.adjustments', 'lines.taxLines', 'addresses.country', 'promotions']);

        return CartResource::make($cart)->withTotals($context);
    }

    /**
     * Domain failures from the cart manager become HTTP semantics: a stock
     * shortage is a validation error on the quantity, touching a completed
     * cart is a conflict.
     */
    protected function mutateCart(Closure $operation): mixed
    {
        try {
            return $operation();
        } catch (InsufficientStockException $exception) {
            throw ValidationException::withMessages(['quantity' => $exception->getMessage()]);
        } catch (CartCompletedException $exception) {
            abort(Response::HTTP_CONFLICT, $exception->getMessage());
        }
    }
}
