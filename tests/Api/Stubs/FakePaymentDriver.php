<?php

declare(strict_types=1);

namespace Tests\Api\Stubs;

use RuntimeException;
use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Drivers\Driver;

final class FakePaymentDriver extends Driver
{
    public bool $failRetrieve = false;

    public int $initiations = 0;

    public int $retrievals = 0;

    public int $cancellations = 0;

    public ?int $lastAmount = null;

    public ?string $lastCancelledReference = null;

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

        return new PaymentResult(
            success: true,
            status: 'cancelled',
            reference: $reference,
        );
    }

    public function handleWebhook(array $payload, array $headers = []): WebhookResult
    {
        // Simulate signature verification failure for a malformed payload.
        if (($payload['action'] ?? null) === 'invalid') {
            throw new RuntimeException('Invalid webhook signature.');
        }

        return new WebhookResult(
            action: (string) ($payload['action'] ?? 'ignored'),
            reference: isset($payload['reference']) ? (string) $payload['reference'] : null,
            amount: isset($payload['amount']) ? (int) $payload['amount'] : null,
            eventId: isset($payload['event_id']) ? (string) $payload['event_id'] : null,
        );
    }

    public function retrievePayment(string $reference): PaymentResult
    {
        $this->retrievals++;

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
