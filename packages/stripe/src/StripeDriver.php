<?php

declare(strict_types=1);

namespace Shopper\Stripe;

use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Drivers\Driver;
use Shopper\Stripe\Exceptions\StripeException;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;

final class StripeDriver extends Driver
{
    private ?StripeClient $client = null;

    public function __construct(
        private readonly string $secretKey,
        private readonly string $publishableKey,
        private readonly string $webhookSecret,
        private readonly string $captureMethod = 'manual',
    ) {}

    public function code(): string
    {
        return 'stripe';
    }

    public function name(): string
    {
        return 'Stripe';
    }

    public function logo(): string
    {
        return shopper_panel_assets('/images/payments/stripe.svg');
    }

    public function isConfigured(): bool
    {
        return filled($this->secretKey);
    }

    public function publishableKey(): string
    {
        return $this->publishableKey;
    }

    public function initiatePayment(int $amount, string $currency, array $context = []): PaymentResult
    {
        try {
            $params = [
                'amount' => $amount,
                'currency' => mb_strtolower($currency),
                'capture_method' => $this->captureMethod,
            ];

            if (isset($context['payment_method'])) {
                $params['payment_method'] = $context['payment_method'];
            }

            if (isset($context['customer'])) {
                $params['customer'] = $context['customer'];
            }

            if (isset($context['metadata'])) {
                $params['metadata'] = $context['metadata'];
            }

            $intent = $this->getClient()->paymentIntents->create($params);

            return new PaymentResult(
                success: true,
                status: $this->mapIntentStatus($intent->status),
                reference: $intent->id,
                clientSecret: $intent->client_secret,
                amount: $intent->amount,
                data: [
                    'stripe_status' => $intent->status,
                    'publishable_key' => $this->publishableKey,
                ],
            );
        } catch (ApiErrorException $e) {
            throw StripeException::fromApiError($e);
        }
    }

    public function authorizePayment(string $reference, array $data = []): PaymentResult
    {
        try {
            $params = [];

            if (isset($data['payment_method'])) {
                $params['payment_method'] = $data['payment_method'];
            }

            if (isset($data['return_url'])) {
                $params['return_url'] = $data['return_url'];
            }

            $intent = $this->getClient()->paymentIntents->confirm($reference, $params);

            return new PaymentResult(
                success: $intent->status !== 'canceled',
                status: $this->mapIntentStatus($intent->status),
                reference: $intent->id,
                clientSecret: $intent->client_secret,
                redirectUrl: $intent->next_action?->redirect_to_url?->url, // @phpstan-ignore property.notFound
                amount: $intent->amount,
                data: ['stripe_status' => $intent->status],
            );
        } catch (ApiErrorException $e) {
            throw StripeException::fromApiError($e);
        }
    }

    public function capturePayment(string $reference, ?int $amount = null): PaymentResult
    {
        try {
            $params = $amount !== null ? ['amount_to_capture' => $amount] : [];
            $intent = $this->getClient()->paymentIntents->capture($reference, $params);

            return new PaymentResult(
                success: $intent->status === 'succeeded',
                status: 'captured',
                reference: $intent->id,
                amount: $intent->amount_received,
                data: ['stripe_status' => $intent->status],
            );
        } catch (ApiErrorException $e) {
            throw StripeException::fromApiError($e);
        }
    }

    public function refundPayment(string $reference, int $amount, ?string $reason = null): PaymentResult
    {
        try {
            $params = [
                'payment_intent' => $reference,
                'amount' => $amount,
            ];

            if ($reason !== null) {
                $params['reason'] = $reason;
            }

            $refund = $this->getClient()->refunds->create($params);

            return new PaymentResult(
                success: $refund->status === 'succeeded',
                status: 'refunded',
                reference: $refund->id,
                amount: $refund->amount,
                data: [
                    'stripe_status' => $refund->status,
                    'payment_intent' => $reference,
                ],
            );
        } catch (ApiErrorException $e) {
            throw StripeException::fromApiError($e);
        }
    }

    public function cancelPayment(string $reference): PaymentResult
    {
        try {
            $intent = $this->getClient()->paymentIntents->cancel($reference);

            return new PaymentResult(
                success: $intent->status === 'canceled',
                status: 'canceled',
                reference: $intent->id,
                amount: $intent->amount,
                data: ['stripe_status' => $intent->status],
            );
        } catch (ApiErrorException $e) {
            throw StripeException::fromApiError($e);
        }
    }

    public function retrievePayment(string $reference): PaymentResult
    {
        try {
            $intent = $this->getClient()->paymentIntents->retrieve($reference);

            return new PaymentResult(
                success: ! in_array($intent->status, ['canceled', 'requires_payment_method'], true),
                status: $this->mapIntentStatus($intent->status),
                reference: $intent->id,
                clientSecret: $intent->client_secret,
                amount: $intent->amount_received ?: $intent->amount,
                data: [
                    'stripe_status' => $intent->status,
                    'payment_method' => $intent->payment_method,
                    'charges' => $intent->latest_charge,
                ],
            );
        } catch (ApiErrorException $e) {
            throw StripeException::fromApiError($e);
        }
    }

    public function handleWebhook(array $payload, array $headers = []): WebhookResult
    {
        $rawBody = $payload['_raw_body'] ?? '';
        $signature = $headers['stripe-signature'] ?? $headers['Stripe-Signature'] ?? '';

        if ($rawBody === '' || $signature === '') {
            throw StripeException::invalidWebhookPayload('Missing raw body or signature.');
        }

        try {
            $event = Webhook::constructEvent($rawBody, $signature, $this->webhookSecret);
        } catch (SignatureVerificationException $e) {
            throw StripeException::invalidWebhookPayload($e->getMessage());
        }

        $object = $event->data->object; // @phpstan-ignore property.notFound

        return match ($event->type) {
            'payment_intent.amount_capturable_updated' => new WebhookResult(
                action: 'authorized',
                reference: $object->id,
                amount: $object->amount_capturable ?? $object->amount,
                data: ['stripe_event' => $event->type],
            ),
            'payment_intent.succeeded' => new WebhookResult(
                action: 'captured',
                reference: $object->id,
                amount: $object->amount_received ?? $object->amount,
                data: ['stripe_event' => $event->type],
            ),
            'payment_intent.payment_failed' => new WebhookResult(
                action: 'failed',
                reference: $object->id,
                amount: $object->amount,
                data: [
                    'stripe_event' => $event->type,
                    'failure_message' => $object->last_payment_error?->message,
                ],
            ),
            'payment_intent.canceled' => new WebhookResult(
                action: 'canceled',
                reference: $object->id,
                amount: $object->amount,
                data: ['stripe_event' => $event->type],
            ),
            'charge.refunded' => new WebhookResult(
                action: 'refunded',
                reference: $object->payment_intent,
                amount: $object->amount_refunded,
                data: ['stripe_event' => $event->type],
            ),
            default => WebhookResult::ignored(),
        };
    }

    private function getClient(): StripeClient
    {
        return $this->client ??= new StripeClient($this->secretKey);
    }

    private function mapIntentStatus(string $status): string
    {
        return match ($status) {
            'requires_payment_method', 'requires_confirmation' => 'pending',
            'requires_action' => 'requires_action',
            'processing' => 'processing',
            'requires_capture' => 'authorized',
            'succeeded' => 'captured',
            'canceled' => 'canceled',
            default => $status,
        };
    }
}
