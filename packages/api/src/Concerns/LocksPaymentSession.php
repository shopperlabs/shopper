<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Closure;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiException;
use Symfony\Component\HttpFoundation\Response;

trait LocksPaymentSession
{
    /**
     * @template TResult
     *
     * @param  Closure(): TResult  $callback
     * @return TResult
     *
     * @throws ApiException
     */
    private function withPaymentSessionLock(CartManager $cartManager, Cart $cart, Closure $callback): mixed
    {
        try {
            return $cartManager->withPaymentSessionLock($cart, $callback);
        } catch (LockTimeoutException $exception) {
            throw $this->paymentSessionInProgress($exception);
        }
    }

    private function paymentSessionInProgress(LockTimeoutException $exception): ApiException
    {
        report($exception);

        return new ApiException(
            Response::HTTP_CONFLICT,
            ErrorCode::PaymentSessionInProgress,
            __('shopper-api::messages.payment.session_in_progress'),
            ['Retry-After' => 3],
        );
    }
}
