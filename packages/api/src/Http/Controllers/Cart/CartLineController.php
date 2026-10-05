<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Cart;

use Illuminate\Http\Request;
use Shopper\Api\Actions\ResolvePurchasableAction;
use Shopper\Api\Concerns\RespondsWithCart;
use Shopper\Api\Http\Requests\Cart\StoreCartLineRequest;
use Shopper\Api\Http\Requests\Cart\UpdateCartLineRequest;
use Shopper\Api\Http\Resources\JsonApiResource;
use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\MissingPriceException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiValidationException;

final class CartLineController
{
    use RespondsWithCart;

    public function __construct(
        private readonly CartManager $cartManager,
    ) {}

    /**
     * Add a line to the cart.
     *
     * Idempotent on the purchasable: adding the same product or variant again
     * increments the existing line instead of duplicating it. Responds with
     * the updated cart; pass `include=lines` to get the lines back.
     */
    public function store(StoreCartLineRequest $request, ResolvePurchasableAction $action, string $cartId): JsonApiResource
    {
        $cart = $this->findCart($request, $cartId);

        $purchasable = $action->execute(
            type: (string) $request->validated('purchasable_type'),
            publicId: (string) $request->validated('purchasable_id'),
        );

        $this->mutateCart(function () use ($cart, $purchasable, $request): CartLine {
            try {
                return $this->cartManager->add(
                    cart: $cart,
                    purchasable: $purchasable,
                    quantity: (int) ($request->validated('quantity') ?? 1),
                    metadata: $request->validated('metadata'),
                );
            } catch (MissingPriceException) {
                throw ApiValidationException::withCode(ErrorCode::PriceMissing, [
                    'purchasable_id' => __('shopper-api::messages.purchasable.missing_price', ['currency' => $cart->currency_code]),
                ]);
            }
        });

        return $this->cartResource($cart);
    }

    /**
     * Update a cart line.
     *
     * Changes the quantity or the metadata of a line, then responds with the
     * recalculated cart.
     */
    public function update(UpdateCartLineRequest $request, string $cartId, string $lineId): JsonApiResource
    {
        $cart = $this->findCart($request, $cartId);
        $line = $this->findLine($cart, $lineId);

        $this->mutateCart(fn (): CartLine => $this->cartManager->update($cart, $line->id, $request->validated()));

        return $this->cartResource($cart);
    }

    /**
     * Remove a cart line.
     *
     * Deletes the line and responds with the recalculated cart rather than a
     * 204, so the storefront can refresh totals without a second call.
     */
    public function destroy(Request $request, string $cartId, string $lineId): JsonApiResource
    {
        $cart = $this->findCart($request, $cartId);
        $line = $this->findLine($cart, $lineId);

        $this->mutateCart(fn () => $this->cartManager->remove($cart, $line->id));

        return $this->cartResource($cart);
    }

    private function findLine(Cart $cart, string $publicId): CartLine
    {
        /** @var CartLine */
        return $cart->lines()->wherePublicId($publicId)->firstOrFail();
    }
}
