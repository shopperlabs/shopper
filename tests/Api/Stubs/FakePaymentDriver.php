<?php

declare(strict_types=1);

namespace Tests\Api\Stubs;

use Closure;
use RuntimeException;
use Shopper\Payment\Contracts\RetrievesRefunds;
use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Drivers\Driver;
use Shopper\Payment\Enum\WebhookAction;
use Shopper\Payment\Exceptions\PaymentException;

final class FakePaymentDriver extends Driver implements RetrievesRefunds
{
    public bool $failRetrieve = false;

    public bool $throwOnRetrieve = false;

    public bool $throwOnInitiate = false;

    public bool $throwOnCancel = false;

    public ?Closure $onInitiate = null;

    public ?Closure $onRetrieve = null;

    public bool $retrieval = true;

    public ?string $retrievedStatus = null;

    /** @var list<string> */
    public array $retrievedStatuses = [];

    public int $initiations = 0;

    public int $retrievals = 0;

    public int $cancellations = 0;

    public ?int $lastAmount = null;

    public string $refundStatus = 'refunded';

    public string $retrievedRefundStatus = 'pending';

    /** @var list<string> */
    public array $unreachableRefunds = [];

    /** @var list<string> */
    public array $refundRetrievals = [];

    public ?string $lastCancelledReference = null;

    /** @var list<array{reference: string, amount: int, context: array<string, mixed>}> */
    public array $refunds = [];

    /** @var array<string, mixed> */
    public array $lastContext = [];

    /** @var array<int, string> */
    public array $idempotencyKeys = [];

    public function __construct(
        private readonly bool $configured = true,
    ) {}

    public function code(): string
    {
        return 'fake';
    }

    public function name(): string
    {
        return 'Fake';
    }

    public function isConfigured(): bool
    {
        return $this->configured;
    }

    public function initiatePayment(int $amount, string $currency, array $context = []): PaymentResult
    {
        $this->initiations++;
        $this->lastAmount = $amount;
        $this->lastContext = $context;
        $this->idempotencyKeys[] = (string) ($context['idempotency_key'] ?? '');

        if ($this->throwOnInitiate) {
            throw PaymentException::apiError($this->code(), 'Provider down.');
        }

        if ($this->onInitiate !== null) {
            ($this->onInitiate)();
        }

        return new PaymentResult(
            success: true,
            status: 'pending',
            reference: 'fake_intent_'.$this->initiations,
            clientSecret: 'fake_secret_'.$this->initiations,
            amount: $amount,
            data: ['publishable_key' => 'pk_fake'],
        );
    }

    public function cancelPayment(string $reference): PaymentResult
    {
        $this->cancellations++;
        $this->lastCancelledReference = $reference;

        if ($this->throwOnCancel) {
            throw PaymentException::apiError($this->code(), 'Intent can no longer be cancelled.');
        }

        return new PaymentResult(
            success: true,
            status: 'cancelled',
            reference: $reference,
        );
    }

    public function refundPayment(string $reference, int $amount, ?string $reason = null, array $context = []): PaymentResult
    {
        $this->refunds[] = ['reference' => $reference, 'amount' => $amount, 'context' => $context];

        return new PaymentResult(
            success: true,
            status: $this->refundStatus,
            reference: 're_'.count($this->refunds),
            amount: $amount,
        );
    }

    public function retrieveRefund(string $refundId): PaymentResult
    {
        $this->refundRetrievals[] = $refundId;

        if (in_array($refundId, $this->unreachableRefunds, true)) {
            throw new RuntimeException('Provider unreachable.');
        }

        if ($this->retrievedRefundStatus === 'failed') {
            return PaymentResult::failed('insufficient_funds', $refundId);
        }

        return new PaymentResult(success: true, status: $this->retrievedRefundStatus, reference: $refundId);
    }

    public function handleWebhook(array $payload, array $headers = []): WebhookResult
    {
        // Simulate signature verification failure for a malformed payload.
        if (($payload['action'] ?? null) === 'invalid') {
            throw new RuntimeException('Invalid webhook signature.');
        }

        return new WebhookResult(
            action: WebhookAction::tryFrom((string) ($payload['action'] ?? '')) ?? WebhookAction::Ignored,
            reference: isset($payload['reference']) ? (string) $payload['reference'] : null,
            amount: isset($payload['amount']) ? (int) $payload['amount'] : null,
            eventId: isset($payload['event_id']) ? (string) $payload['event_id'] : null,
        );
    }

    public function supportsRetrieval(): bool
    {
        return $this->retrieval;
    }

    public function retrievePayment(string $reference): PaymentResult
    {
        $this->retrievals++;

        if ($this->onRetrieve !== null) {
            ($this->onRetrieve)();
        }

        if ($this->throwOnRetrieve) {
            throw new RuntimeException('Provider unreachable.');
        }

        if ($this->retrievedStatuses !== [] || $this->retrievedStatus !== null) {
            return new PaymentResult(
                success: true,
                status: array_shift($this->retrievedStatuses) ?? $this->retrievedStatus,
                reference: $reference,
                amount: $this->lastAmount,
            );
        }

        if ($this->failRetrieve) {
            return new PaymentResult(
                success: false,
                status: 'canceled',
                reference: $reference,
            );
        }

        return new PaymentResult(
            success: true,
            status: 'pending',
            reference: $reference,
            clientSecret: 'fake_secret_resumed',
            amount: $this->lastAmount,
        );
    }
}
