<?php

declare(strict_types=1);

namespace Shopper\Payment;

use Shopper\Core\Contracts\PaymentSessionGateway;
use Shopper\Core\Exceptions\PaymentProviderUnavailableException;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Payment\Contracts\PaymentDriver;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Models\PaymentTransaction;
use Throwable;

final readonly class ProviderPaymentSessionGateway implements PaymentSessionGateway
{
    public function __construct(
        private PaymentManager $paymentManager,
    ) {}

    public function collected(array $session): ?bool
    {
        if ($this->unmanaged($session)) {
            return false;
        }

        $driver = $this->driver($session);

        if (! $driver->supportsRetrieval()) {
            return null;
        }

        return $this->retrieveCollected($driver, $session['reference']);
    }

    public function release(array $session): bool
    {
        if ($this->unmanaged($session)) {
            return true;
        }

        $driver = $this->driver($session);

        if (! $driver->supportsRetrieval() || $this->retrieveCollected($driver, $session['reference'])) {
            return false;
        }

        try {
            if ($driver->cancelPayment($session['reference'])->success) {
                return true;
            }
        } catch (Throwable $exception) {
            report($exception);
        }

        try {
            $result = $driver->retrievePayment($session['reference']);
        } catch (Throwable $exception) {
            throw new PaymentProviderUnavailableException($exception);
        }

        return match (true) {
            $result->isCollected() => false,
            in_array($result->status, ['canceled', 'failed', 'refunded'], true) => true,
            default => throw new PaymentProviderUnavailableException,
        };
    }

    public function refund(array $session): bool
    {
        if ($this->unmanaged($session)) {
            return true;
        }

        $driver = $this->driver($session);

        if (! $driver->supportsRetrieval()) {
            return false;
        }

        try {
            $payment = $driver->retrievePayment($session['reference']);

            $result = match ($payment->status) {
                'captured' => $driver->refundPayment(
                    $session['reference'],
                    $payment->amount ?? (int) $session['amount'],
                    context: ['idempotency_key' => 'refund-'.$session['reference']],
                ),
                'authorized' => $driver->cancelPayment($session['reference']),
                default => null,
            };
        } catch (Throwable $exception) {
            throw new PaymentProviderUnavailableException($exception);
        }

        return $result->success ?? in_array($payment->status, ['pending', 'requires_action', 'canceled', 'failed', 'refunded'], true);
    }

    public function releaseOrder(Order $order): bool
    {
        $transaction = PaymentTransaction::query()
            ->where('order_id', $order->getKey())
            ->where('type', TransactionType::Initiate)
            ->latest('id')
            ->first();

        if ($transaction?->reference === null) {
            return true;
        }

        $session = ['driver' => $transaction->driver, 'reference' => $transaction->reference];

        if ($this->unmanaged($session) || $this->driver($session)->supportsRetrieval()) {
            return $this->release($session);
        }

        try {
            return $this->driver($session)->cancelPayment($transaction->reference)->success;
        } catch (Throwable $exception) {
            report($exception);

            return false;
        }
    }

    /**
     * @param  array<string, mixed>  $session
     */
    private function unmanaged(array $session): bool
    {
        return ! isset($session['reference']) || ($session['driver'] ?? 'manual') === 'manual';
    }

    /**
     * @param  array<string, mixed>  $session
     */
    private function driver(array $session): PaymentDriver
    {
        try {
            return $this->paymentManager->driver($session['driver']);
        } catch (Throwable $exception) {
            throw new PaymentProviderUnavailableException($exception);
        }
    }

    private function retrieveCollected(PaymentDriver $driver, string $reference): bool
    {
        try {
            return $driver->retrievePayment($reference)->isCollected();
        } catch (Throwable $exception) {
            throw new PaymentProviderUnavailableException($exception);
        }
    }
}
