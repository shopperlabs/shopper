<?php

declare(strict_types=1);

namespace Shopper\Payment\Actions;

use Shopper\Payment\Models\PaymentWebhookEvent;

final readonly class SettlePayment
{
    public function __construct(
        private ApplyPaymentEvent $apply,
    ) {}

    public function execute(string $reference): void
    {
        PaymentWebhookEvent::query()
            ->unprocessed()
            ->forReference($reference)
            ->orderBy('id')
            ->get()
            ->sortBy(fn (PaymentWebhookEvent $event): array => [$event->type?->precedence() ?? PHP_INT_MAX, $event->id])
            ->each(fn (PaymentWebhookEvent $event) => $this->apply->execute($event));
    }
}
