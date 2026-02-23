<?php

declare(strict_types=1);

use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Stripe\Exceptions\StripeException;
use Shopper\Stripe\StripeDriver;
use Stripe\Exception\InvalidRequestException;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\StripeClient;

function createDriver(
    string $secretKey = 'sk_test_123',
    string $publishableKey = 'pk_test_123',
    string $webhookSecret = 'whsec_test_123',
    string $captureMethod = 'manual',
): StripeDriver {
    return new StripeDriver($secretKey, $publishableKey, $webhookSecret, $captureMethod);
}

/**
 * @return object{paymentIntents: Mockery\MockInterface, refunds: Mockery\MockInterface}
 */
function injectMockClient(StripeDriver $driver): object
{
    $mockPaymentIntents = Mockery::mock();
    $mockRefunds = Mockery::mock();

    $mockClient = Mockery::mock(StripeClient::class);
    $mockClient->shouldReceive('getService')->with('paymentIntents')->andReturn($mockPaymentIntents);
    $mockClient->shouldReceive('getService')->with('refunds')->andReturn($mockRefunds);

    $reflection = new ReflectionClass($driver);
    $property = $reflection->getProperty('client');
    $property->setValue($driver, $mockClient);

    return (object) [
        'paymentIntents' => $mockPaymentIntents,
        'refunds' => $mockRefunds,
    ];
}

/**
 * @return array{payload: array{_raw_body: string}, headers: array{stripe-signature: string}}
 */
function webhookPayload(string $eventType, array $objectData, string $secret = 'whsec_test_123'): array
{
    $payload = json_encode([
        'id' => 'evt_test_'.bin2hex(random_bytes(8)),
        'object' => 'event',
        'type' => $eventType,
        'data' => [
            'object' => $objectData,
        ],
    ]);

    $timestamp = time();
    $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);

    return [
        'payload' => ['_raw_body' => $payload],
        'headers' => ['stripe-signature' => "t={$timestamp},v1={$signature}"],
    ];
}

describe(StripeDriver::class, function (): void {

    describe('accessors', function (): void {
        it('returns `stripe` as code', function (): void {
            expect(createDriver()->code())->toBe('stripe');
        });

        it('returns `Stripe` as name', function (): void {
            expect(createDriver()->name())->toBe('Stripe');
        });

        it('returns the publishable key', function (): void {
            expect(createDriver(publishableKey: 'pk_test_my_key')->publishableKey())->toBe('pk_test_my_key');
        });

        it('is configured when secret key is filled', function (): void {
            expect(createDriver(secretKey: 'sk_test_123')->isConfigured())->toBeTrue();
        });

        it('is not configured when secret key is empty', function (): void {
            expect(createDriver(secretKey: '')->isConfigured())->toBeFalse();
        });

        it('supports webhooks', function (): void {
            expect(createDriver()->supportsWebhooks())->toBeTrue();
        });

        it('supports refunds', function (): void {
            expect(createDriver()->supportsRefunds())->toBeTrue();
        });
    });

    describe('`initiatePayment()` method', function (): void {
        it('creates a payment intent with basic params', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'requires_payment_method',
                'client_secret' => 'pi_test_123_secret_abc',
                'amount' => 5000,
            ]);

            $mocks->paymentIntents->shouldReceive('create')
                ->once()
                ->with(Mockery::on(
                    fn (array $params): bool => $params['amount'] === 5000
                    && $params['currency'] === 'usd'
                    && $params['capture_method'] === 'manual'
                ))
                ->andReturn($intent);

            $result = $driver->initiatePayment(5000, 'USD');

            expect($result)->toBeInstanceOf(PaymentResult::class)
                ->and($result->success)->toBeTrue()
                ->and($result->status)->toBe('pending')
                ->and($result->reference)->toBe('pi_test_123')
                ->and($result->clientSecret)->toBe('pi_test_123_secret_abc')
                ->and($result->amount)->toBe(5000)
                ->and($result->data['stripe_status'])->toBe('requires_payment_method')
                ->and($result->data['publishable_key'])->toBe('pk_test_123');
        });

        it('passes optional context params', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $mocks->paymentIntents->shouldReceive('create')
                ->once()
                ->with(Mockery::on(
                    fn (array $params): bool => $params['payment_method'] === 'pm_test_123'
                    && $params['customer'] === 'cus_test_456'
                    && $params['metadata'] === ['order_id' => 'ORD-001']
                ))
                ->andReturn(PaymentIntent::constructFrom([
                    'id' => 'pi_test_456',
                    'status' => 'requires_confirmation',
                    'client_secret' => 'pi_test_456_secret',
                    'amount' => 3000,
                ]));

            $result = $driver->initiatePayment(3000, 'EUR', [
                'payment_method' => 'pm_test_123',
                'customer' => 'cus_test_456',
                'metadata' => ['order_id' => 'ORD-001'],
            ]);

            expect($result->success)->toBeTrue()
                ->and($result->reference)->toBe('pi_test_456');
        });

        it('converts currency to lowercase', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $mocks->paymentIntents->shouldReceive('create')
                ->once()
                ->with(Mockery::on(fn (array $params): bool => $params['currency'] === 'eur'))
                ->andReturn(PaymentIntent::constructFrom([
                    'id' => 'pi_test',
                    'status' => 'requires_payment_method',
                    'client_secret' => 'secret',
                    'amount' => 1000,
                ]));

            $driver->initiatePayment(1000, 'EUR');
        });

        it('throws `StripeException` on API error', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $mocks->paymentIntents->shouldReceive('create')
                ->andThrow(new InvalidRequestException('Invalid amount'));

            $driver->initiatePayment(5000, 'USD');
        })->throws(StripeException::class);
    });

    describe('`authorizePayment()` method', function (): void {
        it('confirms a payment intent', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'requires_capture',
                'client_secret' => 'pi_test_123_secret',
                'amount' => 5000,
                'next_action' => null,
            ]);

            $mocks->paymentIntents->shouldReceive('confirm')
                ->once()
                ->with('pi_test_123', Mockery::type('array'))
                ->andReturn($intent);

            $result = $driver->authorizePayment('pi_test_123');

            expect($result->success)->toBeTrue()
                ->and($result->status)->toBe('authorized')
                ->and($result->reference)->toBe('pi_test_123');
        });

        it('passes payment method and return URL', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'requires_action',
                'client_secret' => 'secret',
                'amount' => 5000,
                'next_action' => [
                    'type' => 'redirect_to_url',
                    'redirect_to_url' => ['url' => 'https://3ds.example.com/auth'],
                ],
            ]);

            $mocks->paymentIntents->shouldReceive('confirm')
                ->once()
                ->with('pi_test_123', Mockery::on(
                    fn (array $params): bool => $params['payment_method'] === 'pm_test_456'
                    && $params['return_url'] === 'https://myshop.com/return'
                ))
                ->andReturn($intent);

            $result = $driver->authorizePayment('pi_test_123', [
                'payment_method' => 'pm_test_456',
                'return_url' => 'https://myshop.com/return',
            ]);

            expect($result->status)->toBe('requires_action')
                ->and($result->redirectUrl)->toBe('https://3ds.example.com/auth')
                ->and($result->requiresAction())->toBeTrue();
        });

        it('returns not successful when status is canceled', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'canceled',
                'client_secret' => 'secret',
                'amount' => 5000,
                'next_action' => null,
            ]);

            $mocks->paymentIntents->shouldReceive('confirm')
                ->andReturn($intent);

            $result = $driver->authorizePayment('pi_test_123');

            expect($result->success)->toBeFalse()
                ->and($result->status)->toBe('canceled');
        });

        it('throws `StripeException` on API error', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $mocks->paymentIntents->shouldReceive('confirm')
                ->andThrow(new InvalidRequestException('No such payment intent'));

            $driver->authorizePayment('pi_invalid');
        })->throws(StripeException::class);
    });

    describe('`capturePayment()` method', function (): void {
        it('captures full amount', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'succeeded',
                'amount_received' => 5000,
            ]);

            $mocks->paymentIntents->shouldReceive('capture')
                ->once()
                ->with('pi_test_123', [])
                ->andReturn($intent);

            $result = $driver->capturePayment('pi_test_123');

            expect($result->success)->toBeTrue()
                ->and($result->status)->toBe('captured')
                ->and($result->amount)->toBe(5000);
        });

        it('captures partial amount', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'succeeded',
                'amount_received' => 3000,
            ]);

            $mocks->paymentIntents->shouldReceive('capture')
                ->once()
                ->with('pi_test_123', ['amount_to_capture' => 3000])
                ->andReturn($intent);

            $result = $driver->capturePayment('pi_test_123', 3000);

            expect($result->success)->toBeTrue()
                ->and($result->amount)->toBe(3000);
        });

        it('is not successful when capture fails', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'requires_capture',
                'amount_received' => 0,
            ]);

            $mocks->paymentIntents->shouldReceive('capture')
                ->andReturn($intent);

            $result = $driver->capturePayment('pi_test_123');

            expect($result->success)->toBeFalse();
        });

        it('throws `StripeException` on API error', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $mocks->paymentIntents->shouldReceive('capture')
                ->andThrow(new InvalidRequestException('Already captured'));

            $driver->capturePayment('pi_test_123');
        })->throws(StripeException::class);
    });

    describe('`refundPayment()` method', function (): void {
        it('creates a full refund', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $refund = Refund::constructFrom([
                'id' => 're_test_123',
                'status' => 'succeeded',
                'amount' => 5000,
            ]);

            $mocks->refunds->shouldReceive('create')
                ->once()
                ->with(Mockery::on(
                    fn (array $params): bool => $params['payment_intent'] === 'pi_test_123'
                    && $params['amount'] === 5000
                    && ! isset($params['reason'])
                ))
                ->andReturn($refund);

            $result = $driver->refundPayment('pi_test_123', 5000);

            expect($result->success)->toBeTrue()
                ->and($result->status)->toBe('refunded')
                ->and($result->reference)->toBe('re_test_123')
                ->and($result->amount)->toBe(5000)
                ->and($result->data['payment_intent'])->toBe('pi_test_123');
        });

        it('creates a partial refund with reason', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $refund = Refund::constructFrom([
                'id' => 're_test_456',
                'status' => 'succeeded',
                'amount' => 2500,
            ]);

            $mocks->refunds->shouldReceive('create')
                ->once()
                ->with(Mockery::on(
                    fn (array $params): bool => $params['amount'] === 2500
                    && $params['reason'] === 'requested_by_customer'
                ))
                ->andReturn($refund);

            $result = $driver->refundPayment('pi_test_123', 2500, 'requested_by_customer');

            expect($result->success)->toBeTrue()
                ->and($result->amount)->toBe(2500);
        });

        it('is not successful when refund fails', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $refund = Refund::constructFrom([
                'id' => 're_test_789',
                'status' => 'failed',
                'amount' => 5000,
            ]);

            $mocks->refunds->shouldReceive('create')->andReturn($refund);

            $result = $driver->refundPayment('pi_test_123', 5000);

            expect($result->success)->toBeFalse();
        });

        it('throws `StripeException` on API error', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $mocks->refunds->shouldReceive('create')
                ->andThrow(new InvalidRequestException('Charge already refunded'));

            $driver->refundPayment('pi_test_123', 5000);
        })->throws(StripeException::class);
    });

    describe('`cancelPayment()` method', function (): void {
        it('cancels a payment intent', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'canceled',
                'amount' => 5000,
            ]);

            $mocks->paymentIntents->shouldReceive('cancel')
                ->once()
                ->with('pi_test_123')
                ->andReturn($intent);

            $result = $driver->cancelPayment('pi_test_123');

            expect($result->success)->toBeTrue()
                ->and($result->status)->toBe('canceled')
                ->and($result->amount)->toBe(5000);
        });

        it('is not successful when cancel returns non-canceled status', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'succeeded',
                'amount' => 5000,
            ]);

            $mocks->paymentIntents->shouldReceive('cancel')->andReturn($intent);

            $result = $driver->cancelPayment('pi_test_123');

            expect($result->success)->toBeFalse();
        });

        it('throws `StripeException` on API error', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $mocks->paymentIntents->shouldReceive('cancel')
                ->andThrow(new InvalidRequestException('Cannot cancel'));

            $driver->cancelPayment('pi_test_123');
        })->throws(StripeException::class);
    });

    describe('`retrievePayment()` method', function (): void {
        it('retrieves a successful payment', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'succeeded',
                'client_secret' => 'pi_test_123_secret',
                'amount' => 5000,
                'amount_received' => 5000,
                'payment_method' => 'pm_test_456',
                'latest_charge' => 'ch_test_789',
            ]);

            $mocks->paymentIntents->shouldReceive('retrieve')
                ->once()
                ->with('pi_test_123')
                ->andReturn($intent);

            $result = $driver->retrievePayment('pi_test_123');

            expect($result->success)->toBeTrue()
                ->and($result->status)->toBe('captured')
                ->and($result->amount)->toBe(5000)
                ->and($result->data['payment_method'])->toBe('pm_test_456')
                ->and($result->data['charges'])->toBe('ch_test_789');
        });

        it('returns not successful for canceled payment', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'canceled',
                'client_secret' => 'secret',
                'amount' => 5000,
                'amount_received' => 0,
                'payment_method' => null,
                'latest_charge' => null,
            ]);

            $mocks->paymentIntents->shouldReceive('retrieve')->andReturn($intent);

            $result = $driver->retrievePayment('pi_test_123');

            expect($result->success)->toBeFalse()
                ->and($result->status)->toBe('canceled');
        });

        it('returns not successful for `requires_payment_method` status', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'requires_payment_method',
                'client_secret' => 'secret',
                'amount' => 5000,
                'amount_received' => 0,
                'payment_method' => null,
                'latest_charge' => null,
            ]);

            $mocks->paymentIntents->shouldReceive('retrieve')->andReturn($intent);

            $result = $driver->retrievePayment('pi_test_123');

            expect($result->success)->toBeFalse()
                ->and($result->status)->toBe('pending');
        });

        it('uses `amount_received` when available', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test_123',
                'status' => 'succeeded',
                'client_secret' => 'secret',
                'amount' => 5000,
                'amount_received' => 3000,
                'payment_method' => 'pm_test',
                'latest_charge' => 'ch_test',
            ]);

            $mocks->paymentIntents->shouldReceive('retrieve')->andReturn($intent);

            $result = $driver->retrievePayment('pi_test_123');

            expect($result->amount)->toBe(3000);
        });

        it('throws `StripeException` on API error', function (): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $mocks->paymentIntents->shouldReceive('retrieve')
                ->andThrow(new InvalidRequestException('No such payment intent'));

            $driver->retrievePayment('pi_invalid');
        })->throws(StripeException::class);
    });

    describe('`handleWebhook()` method', function (): void {
        it('handles `payment_intent.succeeded` event', function (): void {
            $driver = createDriver();
            $webhook = webhookPayload('payment_intent.succeeded', [
                'id' => 'pi_test_123',
                'object' => 'payment_intent',
                'amount' => 5000,
                'amount_received' => 5000,
            ]);

            $result = $driver->handleWebhook($webhook['payload'], $webhook['headers']);

            expect($result)->toBeInstanceOf(WebhookResult::class)
                ->and($result->action)->toBe('captured')
                ->and($result->reference)->toBe('pi_test_123')
                ->and($result->amount)->toBe(5000)
                ->and($result->data['stripe_event'])->toBe('payment_intent.succeeded');
        });

        it('handles `payment_intent.payment_failed` event', function (): void {
            $driver = createDriver();
            $webhook = webhookPayload('payment_intent.payment_failed', [
                'id' => 'pi_test_456',
                'object' => 'payment_intent',
                'amount' => 3000,
                'last_payment_error' => ['message' => 'Your card was declined.'],
            ]);

            $result = $driver->handleWebhook($webhook['payload'], $webhook['headers']);

            expect($result->action)->toBe('failed')
                ->and($result->reference)->toBe('pi_test_456')
                ->and($result->amount)->toBe(3000)
                ->and($result->data['failure_message'])->toBe('Your card was declined.');
        });

        it('handles `payment_intent.canceled` event', function (): void {
            $driver = createDriver();
            $webhook = webhookPayload('payment_intent.canceled', [
                'id' => 'pi_test_789',
                'object' => 'payment_intent',
                'amount' => 2000,
            ]);

            $result = $driver->handleWebhook($webhook['payload'], $webhook['headers']);

            expect($result->action)->toBe('canceled')
                ->and($result->reference)->toBe('pi_test_789')
                ->and($result->amount)->toBe(2000);
        });

        it('handles `charge.refunded` event', function (): void {
            $driver = createDriver();
            $webhook = webhookPayload('charge.refunded', [
                'id' => 'ch_test_123',
                'object' => 'charge',
                'payment_intent' => 'pi_test_123',
                'amount_refunded' => 2500,
            ]);

            $result = $driver->handleWebhook($webhook['payload'], $webhook['headers']);

            expect($result->action)->toBe('refunded')
                ->and($result->reference)->toBe('pi_test_123')
                ->and($result->amount)->toBe(2500);
        });

        it('returns ignored result for unknown events', function (): void {
            $driver = createDriver();
            $webhook = webhookPayload('customer.updated', [
                'id' => 'cus_test_123',
                'object' => 'customer',
            ]);

            $result = $driver->handleWebhook($webhook['payload'], $webhook['headers']);

            expect($result->isIgnored())->toBeTrue();
        });

        it('throws exception when raw body is missing', function (): void {
            createDriver()->handleWebhook([], ['stripe-signature' => 'test']);
        })->throws(StripeException::class, 'Missing raw body or signature.');

        it('throws exception when signature is missing', function (): void {
            createDriver()->handleWebhook(['_raw_body' => '{}'], []);
        })->throws(StripeException::class, 'Missing raw body or signature.');

        it('throws exception for invalid signature', function (): void {
            createDriver()->handleWebhook(
                ['_raw_body' => '{"type":"test"}'],
                ['stripe-signature' => 't=123,v1=invalid'],
            );
        })->throws(StripeException::class);
    });

    describe('status mapping', function (): void {
        it('maps all Stripe intent statuses correctly', function (string $stripeStatus, string $expectedStatus): void {
            $driver = createDriver();
            $mocks = injectMockClient($driver);

            $intent = PaymentIntent::constructFrom([
                'id' => 'pi_test',
                'status' => $stripeStatus,
                'client_secret' => 'secret',
                'amount' => 1000,
                'amount_received' => $stripeStatus === 'succeeded' ? 1000 : 0,
                'payment_method' => null,
                'latest_charge' => null,
            ]);

            $mocks->paymentIntents->shouldReceive('retrieve')->andReturn($intent);

            $result = $driver->retrievePayment('pi_test');

            expect($result->status)->toBe($expectedStatus);
        })->with([
            'requires_payment_method' => ['requires_payment_method', 'pending'],
            'requires_confirmation' => ['requires_confirmation', 'pending'],
            'requires_action' => ['requires_action', 'requires_action'],
            'processing' => ['processing', 'processing'],
            'requires_capture' => ['requires_capture', 'authorized'],
            'succeeded' => ['succeeded', 'captured'],
            'canceled' => ['canceled', 'canceled'],
        ]);
    });

})->group('stripe', 'payment');
