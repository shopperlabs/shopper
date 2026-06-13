<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Cart;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Shopper\Api\Concerns\RespondsWithCart;
use Shopper\Api\Http\Requests\Cart\SetPaymentMethodRequest;
use Shopper\Api\Http\Resources\PaymentMethodResource;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Payment\PaymentManager;
use Shopper\Payment\Services\PaymentProcessingService;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class CartPaymentMethodController
{
    use RespondsWithCart;

    public function __construct(
        private readonly PaymentProcessingService $paymentService,
        private readonly PaymentManager $paymentManager,
        private readonly CartManager $cartManager,
    ) {}

    /**
     * List the payment methods available for a cart.
     *
     * Only enabled methods whose driver is configured are offered. A cart
     * bound to a zone is restricted to the methods sold in that zone.
     */
    public function index(Request $request, string $cartId): JsonApiResourceCollection
    {
        $cart = $this->findCart($request, $cartId);

        return PaymentMethodResource::collection($this->availableMethods($cart));
    }

    /**
     * Set the payment method of a cart.
     *
     * The method is referenced by its public id, as listed by the payment
     * methods endpoint. A method outside the cart's offer is rejected.
     */
    public function store(SetPaymentMethodRequest $request, string $cartId): JsonApiResource
    {
        $cart = $this->findCart($request, $cartId);

        $method = $this->availableMethods($cart)->firstWhere(
            'public_id',
            (string) $request->validated('payment_method_id'),
        );

        if (! $method) {
            throw ValidationException::withMessages([
                'payment_method_id' => __('shopper-api::messages.payment.method_not_available'),
            ]);
        }

        $this->mutateCart(fn () => $this->cartManager->setPaymentMethod($cart, $method->id));

        return $this->cartResource($cart->refresh());
    }

    /**
     * @return Collection<int, PaymentMethod>
     */
    private function availableMethods(Cart $cart): Collection
    {
        if ($cart->zone) {
            return $this->paymentService->getMethodsForZone($cart->zone);
        }

        return PaymentMethod::query()
            ->where('is_enabled', true)
            ->get()
            ->filter(fn (PaymentMethod $method): bool => $this->paymentManager->isConfigured($method->driver ?? 'manual'))
            ->values();
    }
}
