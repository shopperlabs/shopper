<?php

declare(strict_types=1);

namespace Shopper\Payment\DataTransferObjects;

use Shopper\Payment\Enum\WebhookAction;

final readonly class WebhookResult
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        public WebhookAction $action,
        public ?string $reference = null,
        public ?int $amount = null,
        public array $data = [],
        public ?string $eventId = null,
    ) {}

    public static function ignored(): self
    {
        return new self(action: WebhookAction::Ignored);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            action: WebhookAction::tryFrom((string) ($payload['action'] ?? '')) ?? WebhookAction::Ignored,
            reference: isset($payload['reference']) ? (string) $payload['reference'] : null,
            amount: isset($payload['amount']) ? (int) $payload['amount'] : null,
            data: is_array($payload['data'] ?? null) ? $payload['data'] : [],
            eventId: isset($payload['event_id']) ? (string) $payload['event_id'] : null,
        );
    }

    public function isIgnored(): bool
    {
        return $this->action === WebhookAction::Ignored;
    }

    /**
     * The provider's id for the refund itself, when the driver supplies it.
     * A refund is journalized under that id rather than the payment
     * reference so a redelivery, or the webhook confirming a refund issued
     * from the admin, collapses onto the row already on file.
     */
    public function refundId(): ?string
    {
        $refundId = $this->data['refund_id'] ?? null;

        return is_string($refundId) ? $refundId : null;
    }

    public function toPaymentResult(): PaymentResult
    {
        return new PaymentResult(
            success: true,
            status: $this->action->value,
            reference: $this->refundId() ?? $this->reference,
            amount: $this->amount,
            data: $this->data,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'action' => $this->action->value,
            'reference' => $this->reference,
            'amount' => $this->amount,
            'data' => $this->data,
            'event_id' => $this->eventId,
        ];
    }
}
