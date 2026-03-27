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

    it('is configured when secret key is set', function (): void {
        config()->set('shopper.stripe.secret_key', 'sk_test_123');

        $driver = new StripeDriver(
            secretKey: (string) config('shopper.stripe.secret_key'),
            publishableKey: '',
            webhookSecret: '',
        );

        expect($driver->isConfigured())->toBeTrue();
    });
})->group('stripe', 'payment');
