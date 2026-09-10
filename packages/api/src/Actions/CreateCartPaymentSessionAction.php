<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Shopper\Api\Support\PaymentSession;
use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Models\Cart;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiException;
use Shopper\Http\Exceptions\ApiValidationException;
use Shopper\Payment\Contracts\PaymentDriver;
use Shopper\Payment\Exceptions\PaymentException;
use Shopper\Payment\PaymentManager;
use Symfony\Component\HttpFoundation\Response;

final readonly class CreateCartPaymentSessionAction
{
    public function __construct(
        private PaymentManager $paymentManager,
        private CartManager $cartManager,
        private CancelPaymentSessionAction $cancelSession,
    ) {}

    /**
     * One collectable intent per cart: concurrent calls are serialized on
     * the cart, so a call that waited resumes what the previous one opened
     * when the driver can retrieve it, instead of opening its own. The lock
     * lives in a shared cache store, never on the cart row, as it spans the
     * round trip to the provider.
     */
    public function execute(Cart $cart): PaymentSession
    {
        $method = $cart->paymentMethod;

        if (! $method) {
            throw ApiValidationException::withCode(ErrorCode::PaymentMethodRequired, [
                'payment_method' => __('shopper-api::messages.payment.method_required_for_session'),
            ]);
        }

        $driverCode = $method->driver ?? 'manual';

        if (! $this->paymentManager->isConfigured($driverCode)) {
            report(PaymentException::notConfigured($driverCode));

            throw new ApiException(
                Response::HTTP_SERVICE_UNAVAILABLE,
                ErrorCode::PaymentMethodNotConfigured,
                __('shopper-api::messages.payment.method_not_configured', ['method' => $method->title]),
            );
        }

        $driver = $this->paymentManager->driver($driverCode);

        try {
            return Cache::lock("cart:payment-session:{$cart->public_id}", 90)->block(3, function () use ($cart, $driver, $driverCode): PaymentSession {
                $cart->unsetRelations()->refresh();

                if ($cart->isCompleted()) {
                    throw new ApiException(Response::HTTP_CONFLICT, ErrorCode::CartCompleted, (new CartCompletedException)->getMessage());
                }

                $amount = $this->cartManager->calculate($cart)->total;

                if ($amount <= 0) {
                    throw ApiValidationException::withCode(ErrorCode::CartNothingToCollect, [
                        'cart' => __('shopper-api::messages.cart.nothing_to_collect'),
                    ]);
                }

                return $this->resumeSession($cart, $driver, $driverCode, $amount)
                    ?? $this->openSession($cart, $driver, $driverCode, $amount);
            });
        } catch (LockTimeoutException $exception) {
            report($exception);

            throw new ApiException(
                Response::HTTP_CONFLICT,
                ErrorCode::PaymentSessionInProgress,
                __('shopper-api::messages.payment.session_in_progress'),
                ['Retry-After' => 3],
            );
        }
    }

    /**
     * A session initiated for the same driver and the same total is still the
     * right one: ask the provider for its current state instead of opening a
     * second intent. Drivers without retrieval (manual, ...) fall through to
     * a fresh initiation.
     */
    private function resumeSession(Cart $cart, PaymentDriver $driver, string $driverCode, int $amount): ?PaymentSession
    {
        $session = $cart->payment_session;

        if (
            ! $session
            || ($session['driver'] ?? null) !== $driverCode
            || ($session['amount'] ?? null) !== $amount
            || ($session['currency'] ?? $cart->currency_code) !== $cart->currency_code
            || ! isset($session['reference'])
        ) {
            return null;
        }

        try {
            $result = $driver->retrievePayment($session['reference']);
        } catch (PaymentException) {
            return null;
        }

        if (! $result->success) {
            return null;
        }

        return new PaymentSession($cart, $driverCode, $result);
    }

    /**
     * The new intent is opened under a key never shared between attempts,
     * as the provider replays the saved outcome of a key for a day, errors
     * included, and refuses it with other parameters. It is stored before
     * the previous intent is cancelled, so the cart never points at nothing:
     * a failed initiation leaves the previous session in place, where a
     * completion still checks its amount. A response lost after the provider
     * opened the intent leaves it unconfirmed there, which collects nothing.
     */
    private function openSession(Cart $cart, PaymentDriver $driver, string $driverCode, int $amount): PaymentSession
    {
        $previous = $cart->payment_session;

        try {
            $result = $driver->initiatePayment(
                amount: $amount,
                currency: $cart->currency_code,
                context: [
                    'idempotency_key' => "cart_{$cart->public_id}_".Str::ulid(),
                    'metadata' => ['cart_id' => (string) $cart->public_id],
                ],
            );
        } catch (PaymentException $exception) {
            report($exception);

            throw $this->providerUnavailable();
        }

        $this->cartManager->setPaymentSession($cart, [
            'driver' => $driverCode,
            'reference' => $result->reference,
            'amount' => $amount,
            'currency' => $cart->currency_code,
        ]);

        $this->cancelSession->execute($previous);

        return new PaymentSession($cart, $driverCode, $result);
    }

    private function providerUnavailable(): ApiException
    {
        return new ApiException(
            Response::HTTP_SERVICE_UNAVAILABLE,
            ErrorCode::PaymentProviderUnavailable,
            __('shopper-api::messages.payment.provider_unavailable'),
            ['Retry-After' => 5],
        );
    }
}
