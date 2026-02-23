<?php

declare(strict_types=1);

namespace Shopper\Payment\Services;

use Illuminate\Support\Collection;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Zone;
use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Models\PaymentTransaction;

final class PaymentProcessingService
{
    /**
     * Get the logo URL for a payment method, with driver logo as fallback.
     */
    public function getLogoUrl(PaymentMethod $method): ?string
    {
        return $method->logoUrl() ?? Payment::driver($method->driver ?? 'manual')->logo();
    }

    /**
     * Get available payment methods for a zone.
     *
     * @return Collection<int, PaymentMethod>
     */
    public function getMethodsForZone(Zone $zone): Collection
    {
        return $zone->paymentMethods()
            ->where('is_enabled', true)
            ->get()
            ->filter(function (PaymentMethod $method): bool {
                $driver = $method->driver ?? 'manual';

                return Payment::isConfigured($driver);
            })
            ->values();
    }

    /**
     * Initiate a payment for an order using its payment method.
     *
     * @param  array<string, mixed>  $context
     */
    public function initiate(Order $order, array $context = []): PaymentResult
    {
        $paymentMethod = $order->paymentMethod;
        $driver = Payment::driver($paymentMethod->driver ?? 'manual');

        $result = $driver->initiatePayment(
            amount: $order->price_amount,
            currency: $order->currency_code,
            context: [
                'order_number' => $order->number,
                'order_id' => $order->id,
                ...$context,
            ],
        );

        $this->recordTransaction(
            order: $order,
            paymentMethod: $paymentMethod,
            driverCode: $driver->code(),
            type: TransactionType::Initiate,
            result: $result,
            amount: $order->price_amount,
        );

        return $result;
    }

    /**
     * Authorize a previously initiated payment.
     *
     * @param  array<string, mixed>  $data
     */
    public function authorize(Order $order, string $reference, array $data = []): PaymentResult
    {
        $paymentMethod = $order->paymentMethod;
        $driver = Payment::driver($paymentMethod->driver ?? 'manual');

        $result = $driver->authorizePayment($reference, $data);

        $this->recordTransaction(
            order: $order,
            paymentMethod: $paymentMethod,
            driverCode: $driver->code(),
            type: TransactionType::Authorize,
            result: $result,
            amount: $result->amount ?? $order->price_amount,
        );

        $this->syncPaymentStatus($order, TransactionType::Authorize, $result);

        return $result;
    }

    /**
     * Capture an authorized payment.
     */
    public function capture(Order $order, string $reference, ?int $amount = null): PaymentResult
    {
        $paymentMethod = $order->paymentMethod;
        $driver = Payment::driver($paymentMethod->driver ?? 'manual');

        $result = $driver->capturePayment($reference, $amount);

        $this->recordTransaction(
            order: $order,
            paymentMethod: $paymentMethod,
            driverCode: $driver->code(),
            type: TransactionType::Capture,
            result: $result,
            amount: $amount ?? $order->price_amount,
        );

        $this->syncPaymentStatus($order, TransactionType::Capture, $result);

        return $result;
    }

    /**
     * Refund a captured payment.
     */
    public function refund(Order $order, string $reference, int $amount, ?string $reason = null): PaymentResult
    {
        $paymentMethod = $order->paymentMethod;
        $driver = Payment::driver($paymentMethod->driver ?? 'manual');

        $result = $driver->refundPayment($reference, $amount, $reason);

        $this->recordTransaction(
            order: $order,
            paymentMethod: $paymentMethod,
            driverCode: $driver->code(),
            type: TransactionType::Refund,
            result: $result,
            amount: $amount,
        );

        $this->syncPaymentStatus($order, TransactionType::Refund, $result);

        return $result;
    }

    /**
     * Cancel a non-captured payment.
     */
    public function cancel(Order $order, string $reference): PaymentResult
    {
        $paymentMethod = $order->paymentMethod;
        $driver = Payment::driver($paymentMethod->driver ?? 'manual');

        $result = $driver->cancelPayment($reference);

        $this->recordTransaction(
            order: $order,
            paymentMethod: $paymentMethod,
            driverCode: $driver->code(),
            type: TransactionType::Cancel,
            result: $result,
            amount: $order->price_amount,
        );

        $this->syncPaymentStatus($order, TransactionType::Cancel, $result);

        return $result;
    }

    /**
     * Get all transactions for an order.
     *
     * @return Collection<int, PaymentTransaction>
     */
    public function getTransactions(Order $order): Collection
    {
        return PaymentTransaction::query()
            ->where('order_id', $order->id)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get the latest successful reference for an order.
     */
    public function getLatestReference(Order $order): ?string
    {
        return PaymentTransaction::query()
            ->where('order_id', $order->id)
            ->successful()
            ->latest()
            ->value('reference');
    }

    private function syncPaymentStatus(Order $order, TransactionType $type, PaymentResult $result): void
    {
        if (! $result->success) {
            return;
        }

        $newPaymentStatus = match ($type) {
            TransactionType::Authorize => PaymentStatus::Authorized,
            TransactionType::Capture => PaymentStatus::Paid,
            TransactionType::Refund => $this->determineRefundStatus($order),
            TransactionType::Cancel => PaymentStatus::Voided,
            default => null,
        };

        if ($newPaymentStatus !== null) {
            $order->update(['payment_status' => $newPaymentStatus]);
        }
    }

    private function determineRefundStatus(Order $order): PaymentStatus
    {
        $totalRefunded = PaymentTransaction::query()
            ->where('order_id', $order->id)
            ->where('type', TransactionType::Refund)
            ->where('status', TransactionStatus::Success)
            ->sum('amount');

        return $totalRefunded >= $order->price_amount
            ? PaymentStatus::Refunded
            : PaymentStatus::PartiallyRefunded;
    }

    private function recordTransaction(
        Order $order,
        PaymentMethod $paymentMethod,
        string $driverCode,
        TransactionType $type,
        PaymentResult $result,
        int $amount,
    ): void {
        PaymentTransaction::query()->create([
            'order_id' => $order->id,
            'payment_method_id' => $paymentMethod->id,
            'driver' => $driverCode,
            'type' => $type,
            'status' => $result->success ? TransactionStatus::Success : TransactionStatus::Failed,
            'amount' => $amount,
            'currency_code' => $order->currency_code,
            'reference' => $result->reference,
            'data' => $result->data ?: null,
            'notes' => $result->message,
        ]);
    }
}
