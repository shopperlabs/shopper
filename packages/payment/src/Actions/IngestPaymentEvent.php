<?php

declare(strict_types=1);

namespace Shopper\Payment\Actions;

use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Models\PaymentWebhookEvent;
use Shopper\Payment\Services\PaymentProcessingService;

final readonly class IngestPaymentEvent
{
    public function __construct(
        private PaymentProcessingService $payments,
        private ApplyPaymentEvent $apply,
    ) {}

    public function execute(string $driver, WebhookResult $result): void
    {
        if ($result->isIgnored() || $result->reference === null) {
            return;
        }

        if ($result->eventId === null) {
            $order = $this->payments->findOrderByReference($result->reference);

            if ($order !== null) {
                $this->payments->apply($order, $driver, $result);
            }

            return;
        }

        $event = PaymentWebhookEvent::journal($driver, $result);

        if ($event !== null) {
            $this->apply->execute($event);
        }
    }
}
