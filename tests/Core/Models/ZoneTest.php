<?php

declare(strict_types=1);

use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Zone;

uses(Tests\Core\TestCase::class);

describe(Zone::class, function (): void {
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

    it('returns N/A for countries name when no countries', function (): void {
        $zone = Zone::factory()->create();

        expect($zone->countries_name)->toBe('N/A');
    });

    it('returns N/A for carriers name when no carriers', function (): void {
        $zone = Zone::factory()->create();

        expect($zone->carriers_name)->toBe('N/A');
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
})->group('zone', 'models');
