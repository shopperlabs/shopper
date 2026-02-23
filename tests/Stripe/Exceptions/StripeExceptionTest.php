<?php

declare(strict_types=1);

use Shopper\Payment\Exceptions\PaymentException;
use Shopper\Stripe\Exceptions\StripeException;
use Stripe\Exception\InvalidRequestException;

describe(StripeException::class, function (): void {
    it('extends `PaymentException`', function (): void {
        $exception = StripeException::invalidWebhookPayload('test');

        expect($exception)->toBeInstanceOf(PaymentException::class);
    });

    describe('`fromApiError()` factory', function (): void {
        it('wraps a Stripe API error with a descriptive message', function (): void {
            $apiError = new InvalidRequestException('Card declined');
            $exception = StripeException::fromApiError($apiError);

            expect($exception->getMessage())->toBe('Stripe API error: Card declined')
                ->and($exception->getPrevious())->toBe($apiError);
        });

        it('preserves the original error code', function (): void {
            $apiError = new InvalidRequestException('Not found', 404);
            $exception = StripeException::fromApiError($apiError);

            expect($exception->getCode())->toBe(404);
        });
    });

    describe('`invalidWebhookPayload()` factory', function (): void {
        it('creates an exception with a descriptive message', function (): void {
            $exception = StripeException::invalidWebhookPayload('Missing signature');

            expect($exception->getMessage())->toBe('Invalid Stripe webhook payload: Missing signature');
        });

        it('has no previous exception', function (): void {
            $exception = StripeException::invalidWebhookPayload('Bad data');

            expect($exception->getPrevious())->toBeNull();
        });
    });
})->group('stripe', 'payment');
