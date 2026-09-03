<?php

declare(strict_types=1);

namespace Shopper\Payment\Actions;

use Illuminate\Support\Facades\DB;
use Shopper\Payment\Models\PaymentWebhookEvent;
use Shopper\Payment\Services\PaymentProcessingService;

final readonly class ApplyPaymentEvent
{
    public function __construct(
        private PaymentProcessingService $payments,
    ) {}

    public function execute(PaymentWebhookEvent $event): bool
    {
        $result = $event->toWebhookResult();
        $order = $result->reference === null ? null : $this->payments->findOrderByReference($result->reference);

        if ($order === null) {
            return false;
        }

        return DB::transaction(function () use ($event, $order, $result): bool {
            if (! $event->claim()) {
                return false;
            }

            $this->payments->apply($order, $event->driver, $result);

            return true;
        });
    }
}
