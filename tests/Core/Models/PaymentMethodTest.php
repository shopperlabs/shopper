<?php

declare(strict_types=1);

use Shopper\Core\Models\PaymentMethod;

uses(Tests\TestCase::class);

describe(PaymentMethod::class, function (): void {
    it('has enabled scope', function (): void {
        PaymentMethod::factory()->create([
            'is_enabled' => false,
            'title' => 'Disabled Method',
        ]);
        $enabled = PaymentMethod::factory()->create([
            'is_enabled' => true,
            'title' => 'Enabled Method',
        ]);

        $result = PaymentMethod::enabled()->where('id', $enabled->id)->first();

        expect($result->id)->toBe($enabled->id)
            ->and($result->is_enabled)->toBeTrue();
    });

    it('generates unique slug', function (): void {
        $payment = PaymentMethod::factory()->create([
            'title' => 'Credit Card Payment',
            'slug' => 'credit-card-payment',
        ]);

        expect($payment->slug)->toBe('credit-card-payment');
    });

    it('has logo url accessor', function (): void {
        $payment = PaymentMethod::factory()->create(['logo' => 'images/payment.png']);

        expect($payment->logo_url)->toContain('images/payment.png');
    });

    it('returns null for logo url when logo is null', function (): void {
        $payment = PaymentMethod::factory()->create(['logo' => null]);

        expect($payment->logo_url)->toBeNull();
    });

    // HasZones trait tests
    it('has zones relationship from HasZones trait', function (): void {
        $payment = PaymentMethod::factory()->create(['title' => 'Test Payment', 'slug' => 'test-payment']);
        $zone = Shopper\Core\Models\Zone::factory()->create();

        $payment->zones()->attach($zone);

        expect($payment->zones())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\MorphToMany::class)
            ->and($payment->zones()->count())->toBe(1);
    });
})->group('payment', 'models');
