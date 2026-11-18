<?php

declare(strict_types=1);

namespace Shopper\Payment\DataTransferObjects;

final readonly class PaymentResult
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        public bool $success,
        public string $status,
        public ?string $reference = null,
        public ?string $clientSecret = null,
        public ?string $redirectUrl = null,
        public ?int $amount = null,
        public ?string $message = null,
        public array $data = [],
    ) {}

    public static function failed(string $message, ?string $reference = null): self
    {
        return new self(
            success: false,
            status: 'failed',
            reference: $reference,
            message: $message,
        );
    }

    /**
     * A refund the provider accepted but settles later. The reference is the
     * provider's id for the refund itself, never the payment reference: the
     * webhook that reports the outcome is matched to the refund through it.
     *
     * @param  array<string, mixed>  $data
     */
    public static function pendingRefund(string $reference, ?int $amount = null, array $data = []): self
    {
        return new self(
            success: true,
            status: 'pending',
            reference: $reference,
            amount: $amount,
            data: $data,
        );
    }

    public function isPending(): bool
    {
        return $this->success && in_array($this->status, ['pending', 'requires_action'], true);
    }

    public function requiresAction(): bool
    {
        return $this->redirectUrl !== null || $this->status === 'requires_action';
    }

    public function isCollected(): bool
    {
        return in_array($this->status, ['authorized', 'captured', 'processing'], true);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'status' => $this->status,
            'reference' => $this->reference,
            'client_secret' => $this->clientSecret,
            'redirect_url' => $this->redirectUrl,
            'amount' => $this->amount,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
