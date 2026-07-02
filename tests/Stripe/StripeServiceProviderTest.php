<?php

declare(strict_types=1);

use Shopper\Payment\Facades\Payment;
use Shopper\Stripe\StripeDriver;
use Shopper\Stripe\StripeServiceProvider;

uses(Tests\Stripe\TestCase::class);

describe(StripeServiceProvider::class, function (): void {
    it('merges config under `shopper.stripe`', function (): void {
        expect(config('shopper.stripe'))->toBeArray()
            ->and(config('shopper.stripe'))->toHaveKeys([
                'secret_key',
                'publishable_key',
                'webhook_secret',
                'capture_method',
            ]);
    });

    it('registers stripe as an available driver', function (): void {
        expect(Payment::availableDrivers())->toContain('stripe');
    });

    it('resolves a `StripeDriver` instance', function (): void {
        expect(Payment::driver('stripe'))->toBeInstanceOf(StripeDriver::class);
    });

    it('returns a logo URL containing `stripe.svg`', function (): void {
        $driver = Payment::driver('stripe');

        expect($driver->logo())->toBeString()->toContain('stripe.svg');
    });

    it('is not configured without credentials', function (): void {
        expect(Payment::isConfigured('stripe'))->toBeFalse();
    });

    it('is configured when the secret key and the webhook secret are set', function (): void {
        $driver = new StripeDriver(
            secretKey: 'sk_test_123',
            publishableKey: '',
            webhookSecret: 'whsec_test_123',
        );

        expect($driver->isConfigured())->toBeTrue();
    });

    it('is not configured when the webhook secret is missing', function (): void {
        $driver = new StripeDriver(
            secretKey: 'sk_test_123',
            publishableKey: '',
            webhookSecret: '',
        );

        expect($driver->isConfigured())->toBeFalse();
    });
})->group('stripe', 'payment');
