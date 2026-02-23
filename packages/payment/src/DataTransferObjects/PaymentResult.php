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

    public function requiresAction(): bool
    {
        return $this->redirectUrl !== null || $this->status === 'requires_action';
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
