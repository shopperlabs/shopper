<?php

declare(strict_types=1);

use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Zone;

uses(Tests\TestCase::class);

describe(Zone::class, function (): void {
    it('checks if zone is enabled', function (): void {
        $zone = Zone::factory()->create(['is_enabled' => true]);

        expect($zone->isEnabled())->toBeTrue();
    });

    it('checks if zone is not enabled', function (): void {
        $zone = Zone::factory()->create(['is_enabled' => false]);

        expect($zone->isEnabled())->toBeFalse();
    });

    it('has enabled scope', function (): void {
        Zone::factory()->create(['is_enabled' => false]);
        $enabled = Zone::factory()->create(['is_enabled' => true]);

        $result = Zone::enabled()->where('id', $enabled->id)->first();

        expect($result->id)->toBe($enabled->id)
            ->and($result->is_enabled)->toBeTrue();
    });

    it('belongs to currency', function (): void {
        $currency = Currency::query()->firstOrCreate(['code' => 'USD'], [
            'name' => 'US Dollar',
            'symbol' => '$',
            'format' => '$1,234.56',
        ]);
        $zone = Zone::factory()->create(['currency_id' => $currency->id]);

        expect($zone->currency)->toBeInstanceOf(Currency::class)
            ->and($zone->currency->id)->toBe($currency->id);
    });

    it('has countries relationship', function (): void {
        $zone = Zone::factory()->create();
        $country = Country::factory()->create();

        $zone->countries()->attach($country);

        expect($zone->countries()->count())->toBe(1);
    });

    it('has paymentMethods relationship', function (): void {
        $zone = Zone::factory()->create();
        $paymentMethod = PaymentMethod::factory()->create();

        $zone->paymentMethods()->attach($paymentMethod);

        expect($zone->paymentMethods()->count())->toBe(1);
    });

    it('has carriers relationship', function (): void {
        $zone = Zone::factory()->create();
        $carrier = Carrier::factory()->create();

        $zone->carriers()->attach($carrier);

        expect($zone->carriers()->count())->toBe(1);
    });

    it('has shippingOptions relationship', function (): void {
        $zone = Zone::factory()->create();
        CarrierOption::factory()->count(3)->create(['zone_id' => $zone->id]);

        expect($zone->shippingOptions()->count())->toBe(3);
    });

    it('returns countries name accessor', function (): void {
        $zone = Zone::factory()->create();
        $country1 = Country::factory()->create(['name' => 'france']);
        $country2 = Country::factory()->create(['name' => 'germany']);

        $zone->countries()->attach([$country1->id, $country2->id]);

        expect($zone->countries_name)->toBeString()
            ->and($zone->countries_name)->toContain('France')
            ->and($zone->countries_name)->toContain('Germany');
    });

    it('returns N/A for countries name when no countries', function (): void {
        $zone = Zone::factory()->create();

        expect($zone->countries_name)->toBe('N/A');
    });

    it('returns carriers name accessor', function (): void {
        $zone = Zone::factory()->create();
        $carrier1 = Carrier::factory()->create(['name' => 'ups']);
        $carrier2 = Carrier::factory()->create(['name' => 'fedex']);

        $zone->carriers()->attach([$carrier1->id, $carrier2->id]);

        expect($zone->carriers_name)->toBeString()
            ->and($zone->carriers_name)->toContain('Ups')
            ->and($zone->carriers_name)->toContain('Fedex');
    });

    it('returns N/A for carriers name when no carriers', function (): void {
        $zone = Zone::factory()->create();

        expect($zone->carriers_name)->toBe('N/A');
    });

    it('returns payments name accessor', function (): void {
        $zone = Zone::factory()->create();
        $payment1 = PaymentMethod::factory()->create(['title' => 'credit card']);
        $payment2 = PaymentMethod::factory()->create(['title' => 'paypal']);

        $zone->paymentMethods()->attach([$payment1->id, $payment2->id]);

        expect($zone->payments_name)->toBeString()
            ->and($zone->payments_name)->toContain('Credit Card')
            ->and($zone->payments_name)->toContain('Paypal');
    });

    it('returns N/A for payments name when no payment methods', function (): void {
        $zone = Zone::factory()->create();

        expect($zone->payments_name)->toBe('N/A');
    });

    it('returns currency code accessor', function (): void {
        $currency = Currency::query()->firstOrCreate(['code' => 'EUR'], [
            'name' => 'Euro',
            'symbol' => '€',
            'format' => '€1,234.56',
        ]);
        $zone = Zone::factory()->create(['currency_id' => $currency->id]);

        expect($zone->currency_code)->toBe('EUR');
    });

    it('casts is_enabled to boolean', function (): void {
        $zone = Zone::factory()->create(['is_enabled' => 1]);

        expect($zone->is_enabled)->toBeTrue()
            ->and($zone->is_enabled)->toBeBool();
    });

    it('casts metadata to array', function (): void {
        $metadata = ['key' => 'value', 'another_key' => 'another_value'];
        $zone = Zone::factory()->create(['metadata' => $metadata]);

        expect($zone->metadata)->toBeArray()
            ->and($zone->metadata)->toBe($metadata);
    });
})->group('zone', 'models');
