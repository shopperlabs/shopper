<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Support\Str;
use Shopper\Api\Concerns\LocksPaymentSession;
use Shopper\Api\Support\PaymentSession;
use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\PaymentSessionCollectedException;
use Shopper\Cart\Exceptions\QuantityRuleViolationException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiException;
use Shopper\Http\Exceptions\ApiValidationException;
use Shopper\Payment\Contracts\PaymentDriver;
use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\Exceptions\PaymentException;
use Shopper\Payment\PaymentManager;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final readonly class CreateCartPaymentSessionAction
{
    use LocksPaymentSession;

    public function __construct(
        private PaymentManager $paymentManager,
        private CartManager $cartManager,
        private RefreshCartShippingAction $refreshShipping,
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
        return $this->withPaymentSessionLock($this->cartManager, $cart, function () use ($cart): PaymentSession {
            $cart->unsetRelations()->refresh();

            if ($cart->isCompleted()) {
                throw new ApiException(Response::HTTP_CONFLICT, ErrorCode::CartCompleted, (new CartCompletedException)->getMessage());
            }

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
            $retrieved = $this->retrieveSession($cart, $driver, $driverCode);

            if ($retrieved?->isCollected()) {
                return new PaymentSession($cart, $driverCode, $retrieved);
            }

            try {
                $this->repriceForPayment($cart);
                $this->refreshShipping($cart);

                $totals = $this->collectableTotals($cart);
                $resumed = $this->resumeSession($cart, $driverCode, $totals->total, $retrieved);

                if ($resumed !== null) {
                    return $resumed;
                }

                if ($cart->payment_session !== null) {
                    $this->cartManager->releasePaymentSession($cart);
                    $totals = $this->collectableTotals($cart);
                }

                return $this->openSession($cart, $driver, $driverCode, $totals);
            } catch (PaymentSessionCollectedException $exception) {
                $collected = $this->retrieveSession($cart, $driver, $driverCode);

                if ($collected?->isCollected()) {
                    return new PaymentSession($cart, $driverCode, $collected);
                }

                throw new ApiException(Response::HTTP_CONFLICT, ErrorCode::PaymentSessionCollected, $exception->getMessage());
            }
        });
    }

    /**
     * A session initiated for the same driver and the same total is still the
     * right one: ask the provider for its current state instead of opening a
     * second intent. Drivers without retrieval (manual, ...) fall through to
     * a fresh initiation.
     */
    private function resumeSession(Cart $cart, string $driverCode, int $amount, ?PaymentResult $retrieved): ?PaymentSession
    {
        $session = $cart->payment_session;

        if (
            $retrieved === null
            || ! $retrieved->success
            || ! $session
            || ($session['amount'] ?? null) !== $amount
            || ($session['currency'] ?? $cart->currency_code) !== $cart->currency_code
            || $this->cartManager->holdsLapsedPromotion($cart)
        ) {
            return null;
        }

        return new PaymentSession($cart, $driverCode, $retrieved);
    }

    private function collectableTotals(Cart $cart): CartPipelineContext
    {
        $totals = $this->cartManager->calculate($cart);

        if ($totals->total <= 0) {
            throw ApiValidationException::withCode(ErrorCode::CartNothingToCollect, [
                'cart' => __('shopper-api::messages.cart.nothing_to_collect'),
            ]);
        }

        return $totals;
    }

    private function retrieveSession(Cart $cart, PaymentDriver $driver, string $driverCode): ?PaymentResult
    {
        $session = $cart->payment_session;

        if (! $session || ($session['driver'] ?? null) !== $driverCode || ! isset($session['reference']) || ! $driver->supportsRetrieval()) {
            return null;
        }

        try {
            return $driver->retrievePayment($session['reference']);
        } catch (Throwable $exception) {
            report($exception);

            throw $this->providerUnavailable();
        }
    }

    /**
     * The new intent is opened under a key never shared between attempts,
     * as the provider replays the saved outcome of a key for a day, errors
     * included, and refuses it with other parameters. A response lost
     * after the provider opened the intent leaves it unconfirmed there,
     * which collects nothing.
     */
    private function openSession(Cart $cart, PaymentDriver $driver, string $driverCode, CartPipelineContext $totals): PaymentSession
    {
        try {
            $result = $driver->initiatePayment(
                amount: $totals->total,
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

        $stored = $this->cartManager->setPaymentSession($cart, [
            'driver' => $driverCode,
            'reference' => $result->reference,
            'amount' => $totals->total,
            'currency' => $cart->currency_code,
            'tax_inclusive' => $totals->taxInclusive,
        ]);

        if (! $stored) {
            rescue(fn (): PaymentResult => $driver->cancelPayment((string) $result->reference));

            throw new ApiException(Response::HTTP_CONFLICT, ErrorCode::CartCompleted, (new CartCompletedException)->getMessage());
        }

        return new PaymentSession($cart, $driverCode, $result);
    }

    private function repriceForPayment(Cart $cart): void
    {
        try {
            $changes = $this->cartManager->reprice($cart);
        } catch (CartCompletedException $exception) {
            throw new ApiException(Response::HTTP_CONFLICT, ErrorCode::CartCompleted, $exception->getMessage());
        }

        try {
            $this->cartManager->assertSellable($cart);
        } catch (QuantityRuleViolationException $exception) {
            throw ApiValidationException::withCode(ErrorCode::QuantityRuleViolated, ['cart' => $exception->getMessage()], $exception->context());
        }

        if ($changes->contains(fn (array $change): bool => $change['to'] > $change['from'])) {
            throw ApiValidationException::withCode(ErrorCode::PriceChanged, [
                'cart' => __('shopper-cart::exceptions.price_changed'),
            ]);
        }
    }

    private function refreshShipping(Cart $cart): void
    {
        $cart->loadMissing(['zone.currency', 'zone.carriers', 'lines.purchasable', 'addresses.country']);

        $this->refreshShipping->execute($cart, fn (): bool => false);
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
