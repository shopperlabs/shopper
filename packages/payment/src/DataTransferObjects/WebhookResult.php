<?php

declare(strict_types=1);

namespace Shopper\Payment\DataTransferObjects;

final readonly class WebhookResult
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        public string $action,
        public ?string $reference = null,
        public ?int $amount = null,
        public array $data = [],
    ) {}

    public static function ignored(): self
    {
        return new self(action: 'ignored');
    }

    public function isIgnored(): bool
    {
        return $this->action === 'ignored';
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'action' => $this->action,
            'reference' => $this->reference,
            'amount' => $this->amount,
            'data' => $this->data,
        ];
    }
}
