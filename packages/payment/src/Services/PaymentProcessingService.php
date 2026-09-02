<?php

declare(strict_types=1);

namespace Shopper\Payment\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Actions\ReleaseCampaignBudget;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Events\Orders\OrderCancelled;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Zone;
use Shopper\Payment\Contracts\PaymentDriver;
use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Enum\WebhookAction;
use Shopper\Payment\Events\PaymentFailed;
use Shopper\Payment\Exceptions\PaymentException;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Models\PaymentTransaction;

final class PaymentProcessingService
{
    /**
     * Get the logo URL for a payment method from its driver.
     */
    public function getLogoUrl(PaymentMethod $method): ?string
    {
        return $method->logo();
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
            ->filter(fn (PaymentMethod $method): bool => Payment::isConfigured($method->driver ?? 'manual'))
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
        $driver = $this->resolveDriver($paymentMethod);

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
            actorId: auth()->id(),
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
        $driver = $this->resolveDriver($paymentMethod);

        $result = $driver->authorizePayment($reference, $data);

        $this->recordTransaction(
            order: $order,
            paymentMethod: $paymentMethod,
            driverCode: $driver->code(),
            type: TransactionType::Authorize,
            result: $result,
            amount: $result->amount ?? $order->price_amount,
            actorId: auth()->id(),
        );

        $this->syncPaymentStatus($order, TransactionType::Authorize, $result);

        return $result;
    }

    /**
     * Capture an authorized payment.
     */
    public function capture(Order $order, string $reference, ?int $amount = null): PaymentResult
    {
        if ($order->payment_status === PaymentStatus::Paid) {
            return new PaymentResult(
                success: true,
                status: 'captured',
                reference: $reference,
                amount: $amount ?? $order->price_amount,
            );
        }

        $paymentMethod = $order->paymentMethod;
        $driver = $this->resolveDriver($paymentMethod);

        $result = $driver->capturePayment($reference, $amount);

        $this->recordTransaction(
            order: $order,
            paymentMethod: $paymentMethod,
            driverCode: $driver->code(),
            type: TransactionType::Capture,
            result: $result,
            amount: $amount ?? $order->price_amount,
            actorId: auth()->id(),
        );

        $this->syncPaymentStatus($order, TransactionType::Capture, $result);

        return $result;
    }

    /**
     * Refund a captured payment.
     */
    public function refund(Order $order, string $reference, int $amount, ?string $reason = null): PaymentResult
    {
        $this->assertRefundable($order, $amount);

        $paymentMethod = $order->paymentMethod;
        $driver = $this->resolveDriver($paymentMethod);

        $result = $driver->refundPayment($reference, $amount, $reason, [
            'idempotency_key' => $this->refundIdempotencyKey($order, $reference, $amount),
        ]);

        $this->recordTransaction(
            order: $order,
            paymentMethod: $paymentMethod,
            driverCode: $driver->code(),
            type: TransactionType::Refund,
            result: $result,
            amount: $amount,
            actorId: auth()->id(),
        );

        $this->syncPaymentStatus($order, TransactionType::Refund, $result);

        $this->releaseCampaignBudget($order);

        return $result;
    }

    /**
     * Cancel a non-captured payment.
     */
    public function cancel(Order $order, string $reference): PaymentResult
    {
        $paymentMethod = $order->paymentMethod;
        $driver = $this->resolveDriver($paymentMethod);

        $result = $driver->cancelPayment($reference);

        $this->recordTransaction(
            order: $order,
            paymentMethod: $paymentMethod,
            driverCode: $driver->code(),
            type: TransactionType::Cancel,
            result: $result,
            amount: $order->price_amount,
            actorId: auth()->id(),
        );

        $this->syncPaymentStatus($order, TransactionType::Cancel, $result);

        return $result;
    }

    /**
     * Apply what the provider said about a payment to the order it settles.
     * Runs under the order row lock so the outcome row, the status transition
     * and the redelivery guard commit together: a duplicate can never slip
     * between the guard and the insert, and a failure never leaves a capture
     * journalized on an order that is still unpaid.
     */
    public function apply(Order $order, string $driver, WebhookResult $result): void
    {
        DB::transaction(function () use ($order, $driver, $result): void {
            /** @var Order $order */
            $order = resolve(Order::class)::query()->lockForUpdate()->findOrFail($order->getKey());

            match ($result->action) {
                WebhookAction::Authorized => $this->recordWebhookOutcome($order, $driver, TransactionType::Authorize, $result),
                WebhookAction::Captured => $this->recordWebhookOutcome($order, $driver, TransactionType::Capture, $result),
                WebhookAction::Refunded => $this->processWebhookRefund($order, $driver, $result),
                WebhookAction::Canceled => $this->processWebhookCancellation($order, $driver, $result),
                WebhookAction::Failed => $this->processWebhookFailure($order, $driver, $result),
                WebhookAction::Ignored => null,
            };
        });
    }

    public function findOrderByReference(string $reference): ?Order
    {
        return PaymentTransaction::query()
            ->where('reference', $reference)
            ->latest()
            ->first()?->order;
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

    /**
     * Give the campaign budget back once an order is fully refunded, so a
     * reversed redemption stops counting against the cap. Partial refunds keep
     * the reservation, and the release action is idempotent on retries.
     */
    private function releaseCampaignBudget(Order $order): void
    {
        if ($order->refresh()->payment_status !== PaymentStatus::Refunded) {
            return;
        }

        $campaign = $order->discount?->campaign;

        if ($campaign === null) {
            return;
        }

        resolve(ReleaseCampaignBudget::class)->execute($campaign, $order->getKey(), actor: 'order-refunded');
    }

    private function resolveDriver(PaymentMethod $method): PaymentDriver
    {
        return Payment::driver($method->driver ?? 'manual');
    }

    /**
     * A retried refund reuses the same key so the gateway collapses it into
     * one refund: a timed-out attempt is never journalized, so the sequence
     * is unchanged on retry, while every journalized attempt (successful or
     * declined) moves the sequence forward for the next distinct refund.
     */
    private function refundIdempotencyKey(Order $order, string $reference, int $amount): string
    {
        $sequence = PaymentTransaction::query()
            ->where('order_id', $order->getKey())
            ->where('type', TransactionType::Refund)
            ->count();

        return "refund:{$order->getKey()}:{$reference}:{$amount}:{$sequence}";
    }

    private function syncPaymentStatus(Order $order, TransactionType $type, PaymentResult $result): void
    {
        if (! $result->success) {
            if (in_array($type, [TransactionType::Authorize, TransactionType::Capture, TransactionType::Refund], strict: true)) {
                event(new PaymentFailed($order, $type, $result->message));
            }

            return;
        }

        $newPaymentStatus = match ($type) {
            TransactionType::Authorize => PaymentStatus::Authorized,
            TransactionType::Capture => PaymentStatus::Paid,
            TransactionType::Refund => $this->determineRefundStatus($order),
            TransactionType::Cancel => PaymentStatus::Voided,
            default => null,
        };

        // An invalid transition is skipped, not thrown: webhooks arrive out of
        // order, and a late `captured` must never pull a refunded order back
        // to paid. The transaction row above keeps the full audit trail.
        if ($newPaymentStatus !== null && $order->canTransitionPaymentTo($newPaymentStatus)) {
            $order->transitionPaymentTo($newPaymentStatus);
        }
    }

    private function determineRefundStatus(Order $order): PaymentStatus
    {
        return $this->refundedTotal($order) >= $this->capturedTotal($order)
            ? PaymentStatus::Refunded
            : PaymentStatus::PartiallyRefunded;
    }

    /**
     * The domain guard on outgoing refunds, independent of any gateway-side
     * backstop: the payment must be in a refundable state, and the cumulative
     * refunds can never exceed what was captured. Orders marked paid manually
     * carry no capture transaction, so the order total is the ceiling there.
     */
    private function assertRefundable(Order $order, int $amount): void
    {
        if (! in_array($order->payment_status, [PaymentStatus::Paid, PaymentStatus::PartiallyRefunded], strict: true)) {
            throw PaymentException::refundNotAllowed($order->payment_status->value);
        }

        $refundable = $this->capturedTotal($order) - $this->refundedTotal($order);

        if ($amount > $refundable) {
            throw PaymentException::refundExceedsRefundable($amount, $refundable);
        }
    }

    private function capturedTotal(Order $order): int
    {
        $captured = (int) PaymentTransaction::query()
            ->where('order_id', $order->getKey())
            ->where('type', TransactionType::Capture)
            ->where('status', TransactionStatus::Success)
            ->sum('amount');

        return $captured ?: $order->price_amount;
    }

    private function refundedTotal(Order $order): int
    {
        return (int) PaymentTransaction::query()
            ->where('order_id', $order->getKey())
            ->where('type', TransactionType::Refund)
            ->where('status', TransactionStatus::Success)
            ->sum('amount');
    }

    private function recordTransaction(
        Order $order,
        PaymentMethod $paymentMethod,
        string $driverCode,
        TransactionType $type,
        PaymentResult $result,
        int $amount,
        ?int $actorId = null,
    ): void {
        PaymentTransaction::query()->create([
            'order_id' => $order->id,
            'payment_method_id' => $paymentMethod->id,
            'user_id' => $actorId,
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

    /**
     * A provider settles a reference once: a second successful authorize,
     * capture or cancel for the same payment reference is a redelivery, and
     * a second refund under the same refund id is the webhook confirming a
     * refund already journalized. Either duplicate would inflate a total the
     * refund ceiling is computed from.
     */
    private function alreadyRecorded(Order $order, TransactionType $type, ?string $reference): bool
    {
        if ($reference === null) {
            return false;
        }

        return PaymentTransaction::query()
            ->where('order_id', $order->getKey())
            ->where('type', $type)
            ->where('status', TransactionStatus::Success)
            ->where('reference', $reference)
            ->exists();
    }

    private function recordWebhookOutcome(Order $order, string $driver, TransactionType $type, WebhookResult $result): void
    {
        $reference = $type === TransactionType::Refund ? $result->refundId() : $result->reference;

        if ($this->alreadyRecorded($order, $type, $reference)) {
            return;
        }

        $paymentResult = $result->toPaymentResult();

        $this->recordTransaction(
            order: $order,
            paymentMethod: $order->paymentMethod,
            driverCode: $driver,
            type: $type,
            result: $paymentResult,
            amount: $result->amount ?? $order->price_amount,
        );

        $this->syncPaymentStatus($order, $type, $paymentResult);
    }

    private function processWebhookRefund(Order $order, string $driver, WebhookResult $result): void
    {
        $this->recordWebhookOutcome($order, $driver, TransactionType::Refund, $result);
        $this->releaseCampaignBudget($order);
    }

    private function processWebhookCancellation(Order $order, string $driver, WebhookResult $result): void
    {
        $this->recordWebhookOutcome($order, $driver, TransactionType::Cancel, $result);
        $this->cancelOrder($order);
    }

    /**
     * A failed attempt is not the provider's last word: the intent stays
     * open for the customer to retry, so the order stays pending with its
     * stock reserved, and shopper:orders:reclaim cancels it if no payment
     * ever lands. A failure reported after the payment settled is noise.
     */
    private function processWebhookFailure(Order $order, string $driver, WebhookResult $result): void
    {
        if ($order->payment_status !== PaymentStatus::Pending) {
            return;
        }

        $message = $result->data['failure_message'] ?? null;

        $paymentResult = PaymentResult::failed(
            is_string($message) ? $message : 'Payment failed.',
            $result->reference,
        );

        $this->recordTransaction(
            order: $order,
            paymentMethod: $order->paymentMethod,
            driverCode: $driver,
            type: TransactionType::Capture,
            result: $paymentResult,
            amount: $result->amount ?? $order->price_amount,
        );

        $this->syncPaymentStatus($order, TransactionType::Capture, $paymentResult);
    }

    private function cancelOrder(Order $order): void
    {
        if ($order->payment_status === PaymentStatus::Paid
            || ! $order->canBeCancelled()
            || ! $order->canTransitionTo(OrderStatus::Cancelled)
        ) {
            return;
        }

        $order->transitionTo(OrderStatus::Cancelled);

        event(new OrderCancelled($order));
    }
}
