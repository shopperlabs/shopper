<?php

declare(strict_types=1);

namespace Shopper\Payment\Contracts;

use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Enum\PaymentMode;

interface PaymentDriver
{
    public function code(): string;

    public function name(): string;

    public function logo(): ?string;

    public function isConfigured(): bool;

    /**
     * Return the live/test mode of the driver, or null when the driver has no
     * such notion (manual providers, unconfigured drivers).
     */
    public function mode(): ?PaymentMode;

    public function supportsWebhooks(): bool;

    public function supportsRefunds(): bool;

    /**
     * Whether the provider can be asked for the current state of a payment.
     */
    public function supportsRetrieval(): bool;

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
     * Refund a captured payment (full or partial). A caller-supplied
     * `idempotency_key` in the context must make a retried refund collapse
     * into a single refund at the gateway.
     *
     * @param  array<string, mixed>  $context
     */
    public function refundPayment(string $reference, int $amount, ?string $reason = null, array $context = []): PaymentResult;

    /**
     * Cancel a non-captured payment.
     */
    public function cancelPayment(string $reference): PaymentResult;

    /**
     * Retrieve the current state of a payment from the provider.
     */
    public function retrievePayment(string $reference): PaymentResult;

    /**
     * Process an incoming webhook event from the provider. The reference is
     * the payment reference the order was initiated with, and the event id is
     * what makes a redelivery collapse. A refund event carries the amount of
     * that refund alone, never a cumulative total, and its provider refund id
     * under `data['refund_id']`.
     *
     * @param  array<string, mixed>  $payload
     * @param  array<string, mixed>  $headers
     */
    public function handleWebhook(array $payload, array $headers = []): WebhookResult;
}
