<?php

declare(strict_types=1);

namespace Shopper\Payment\Contracts;

use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\DataTransferObjects\WebhookResult;

interface PaymentDriver
{
    public function code(): string;

    public function name(): string;

    public function logo(): ?string;

    public function isConfigured(): bool;

    public function supportsWebhooks(): bool;

    public function supportsRefunds(): bool;

    /**
     * Initiate a payment session with the provider.
     *
     * Returns the data needed by the frontend (client_secret, order_id, etc.).
     *
     * @param  array<string, mixed>  $context
     */
    public function initiatePayment(int $amount, string $currency, array $context = []): PaymentResult;

    /**
     * Authorize a previously initiated payment.
     *
     * @param  array<string, mixed>  $data
     */
    public function authorizePayment(string $reference, array $data = []): PaymentResult;

    /**
     * Capture an authorized payment (full or partial).
     */
    public function capturePayment(string $reference, ?int $amount = null): PaymentResult;

    /**
     * Refund a captured payment (full or partial).
     */
    public function refundPayment(string $reference, int $amount, ?string $reason = null): PaymentResult;

    /**
     * Cancel a non-captured payment.
     */
    public function cancelPayment(string $reference): PaymentResult;

    /**
     * Retrieve the current state of a payment from the provider.
     */
    public function retrievePayment(string $reference): PaymentResult;

    /**
     * Process an incoming webhook event from the provider.
     *
     * @param  array<string, mixed>  $payload
     * @param  array<string, mixed>  $headers
     */
    public function handleWebhook(array $payload, array $headers = []): WebhookResult;
}
