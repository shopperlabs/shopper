<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Cart;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Shopper\Api\Actions\CreateCartPaymentSessionAction;
use Shopper\Api\Concerns\RespondsWithCart;
use Shopper\Api\Http\Resources\PaymentSessionResource;
use Shopper\Cart\Exceptions\CartCompletedException;
use Symfony\Component\HttpFoundation\Response;

final class PaymentSessionController
{
    use RespondsWithCart;

    public function __construct(
        private readonly CreateCartPaymentSessionAction $action,
    ) {}

    /**
     * Open a payment session for the cart.
     *
     * The driver of the cart's payment method initiates the collection
     * (a Stripe payment intent, ...) for the pipeline-computed total and the
     * response carries what the storefront needs to confirm it: client
     * secret, publishable key, redirect url. Posting again with an unchanged
     * total resumes the same session.
     */
    public function store(Request $request, string $cartId): JsonResponse
    {
        $cart = $this->findCart($request, $cartId);

        if ($cart->isCompleted()) {
            abort(Response::HTTP_CONFLICT, (new CartCompletedException)->getMessage());
        }

        $session = $this->action->execute($cart);

        return PaymentSessionResource::make($session)
            ->toResponse($request)
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
