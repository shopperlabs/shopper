<?php

declare(strict_types=1);

namespace Shopper\Payment\Actions;

use Shopper\Core\Models\Contracts\Order;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Enum\WebhookAction;
use Shopper\Payment\PaymentManager;
use Shopper\Payment\Services\PaymentProcessingService;

final readonly class SyncPaymentWithProvider
{
    public function __construct(
        private PaymentManager $paymentManager,
        private PaymentProcessingService $payments,
    ) {}

    public function execute(Order $order, string $reference): void
    {
        if (! $order->refresh()->isAwaitingPayment()) {
            return;
        }

        $method = $order->paymentMethod;

        if ($method === null) {
            return;
        }

        $driver = $this->paymentManager->driver($method->driver ?? 'manual');

        if (! $driver->supportsRetrieval()) {
            return;
        }

        $state = $driver->retrievePayment($reference);
        $action = WebhookAction::tryFrom($state->status);

        if (! in_array($action, [WebhookAction::Authorized, WebhookAction::Captured, WebhookAction::Canceled], strict: true)) {
            return;
        }

        $this->payments->apply($order, $driver->code(), new WebhookResult(
            action: $action,
            reference: $reference,
            amount: $state->amount,
            data: $state->data,
        ));
    }
}
