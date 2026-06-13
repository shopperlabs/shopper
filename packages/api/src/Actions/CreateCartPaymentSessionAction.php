<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Validation\ValidationException;
use Shopper\Api\Support\PaymentSession;
use Shopper\Cart\CartManager;
use Shopper\Cart\Models\Cart;
use Shopper\Payment\Contracts\PaymentDriver;
use Shopper\Payment\Exceptions\PaymentException;
use Shopper\Payment\PaymentManager;
use Throwable;

final readonly class CreateCartPaymentSessionAction
{
    public function __construct(
        private PaymentManager $paymentManager,
        private CartManager $cartManager,
    ) {}

    /**
     * Start (or resume) a payment session for the cart with the driver of its
     * payment method. The amount is always the pipeline-computed total, never
     * a client value. Posting again with an unchanged total resumes the
     * existing session instead of opening a duplicate intent at the provider;
     * a total that moved (line added, shipping picked) opens a fresh one.
     */
    public function execute(Cart $cart): PaymentSession
    {
        $method = $cart->paymentMethod;

        if (! $method) {
            throw ValidationException::withMessages([
                'payment_method' => __('shopper-api::messages.payment.method_required_for_session'),
            ]);
        }

        $driverCode = $method->driver ?? 'manual';
        $driver = $this->paymentManager->driver($driverCode);

        if (! $driver->isConfigured()) {
            throw ValidationException::withMessages([
                'payment_method' => __('shopper-api::messages.payment.method_not_configured', ['method' => $method->title]),
            ]);
        }

        $amount = $this->cartManager->calculate($cart)->total;

        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'cart' => __('shopper-api::messages.cart.nothing_to_collect'),
            ]);
        }

        $existing = $this->resumeSession($cart, $driver, $driverCode, $amount);

        if ($existing) {
            return $existing;
        }

        $this->cancelReplacedSession($cart->payment_session);

        // The idempotency key is versioned per attempt, never derived from the
        // amount: providers cache responses by key (Stripe: 24h), so an
        // amount-based key would resurrect a stale intent when a cart total
        // round-trips back to a previous value.
        $version = (int) ($cart->payment_session['version'] ?? 0) + 1;

        $result = $driver->initiatePayment(
            amount: $amount,
            currency: $cart->currency_code,
            context: [
                'idempotency_key' => "cart_{$cart->public_id}_v{$version}",
                'metadata' => ['cart_id' => (string) $cart->public_id],
            ],
        );

        $cart->update([
            'payment_session' => [
                'driver' => $driverCode,
                'reference' => $result->reference,
                'amount' => $amount,
                'currency' => $cart->currency_code,
                'version' => $version,
            ],
        ]);

        return new PaymentSession($cart, $driverCode, $result);
    }

    /**
     * A replaced session leaves an open intent at the provider; cancel it
     * best-effort so abandoned intents do not pile up in the provider
     * dashboard. Failures are swallowed: the intent may already be cancelled
     * or confirmed, or the driver may not support cancellation at all.
     *
     * @param  array<string, mixed>|null  $session
     */
    private function cancelReplacedSession(?array $session): void
    {
        if (! $session || ! isset($session['reference'])) {
            return;
        }

        try {
            $this->paymentManager
                ->driver($session['driver'] ?? 'manual')
                ->cancelPayment($session['reference']);
        } catch (Throwable) {
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
}
