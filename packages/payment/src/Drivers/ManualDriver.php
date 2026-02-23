<?php

declare(strict_types=1);

namespace Shopper\Payment\Drivers;

use Illuminate\Support\Str;
use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\DataTransferObjects\WebhookResult;

/**
 * Manual driver for payment methods without API integration.
 * Acts as a cash-on-delivery (COD) or bank transfer placeholder.
 */
final class ManualDriver extends Driver
{
    public function code(): string
    {
        return 'manual';
    }

    public function name(): string
    {
        return 'Manual';
    }

    public function logo(): string
    {
        return shopper_panel_assets('/images/payments/cod.svg');
    }

    public function isConfigured(): bool
    {
        return true;
    }

    public function supportsWebhooks(): bool
    {
        return false;
    }

    public function supportsRefunds(): bool
    {
        return false;
    }

    public function initiatePayment(int $amount, string $currency, array $context = []): PaymentResult
    {
        return new PaymentResult(
            success: true,
            status: 'authorized',
            reference: 'manual_'.Str::ulid(),
            amount: $amount,
        );
    }

    public function authorizePayment(string $reference, array $data = []): PaymentResult
    {
        return new PaymentResult(
            success: true,
            status: 'authorized',
            reference: $reference,
        );
    }

    public function capturePayment(string $reference, ?int $amount = null): PaymentResult
    {
        return new PaymentResult(
            success: true,
            status: 'captured',
            reference: $reference,
            amount: $amount,
        );
    }

    public function handleWebhook(array $payload, array $headers = []): WebhookResult
    {
        return WebhookResult::ignored();
    }
}
