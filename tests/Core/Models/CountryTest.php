<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Zone;

uses(Tests\TestCase::class);

describe(Country::class, function (): void {
    it('has zones relationship from HasZones trait', function (): void {
        $country = Country::factory()->create();
        $zone = Zone::factory()->create();

        $country->zones()->attach($zone);

        expect($country->zones())->toBeInstanceOf(MorphToMany::class)
            ->and($country->zones()->count())->toBe(1);
    });

    it('returns svg flag accessor', function (): void {
        $country = Country::factory()->create(['cca2' => 'US']);

        expect($country->svg_flag)->toBeString()
            ->and($country->svg_flag)->toContain('us.svg');
    });

    it('casts phone_calling_code to array', function (): void {
        $callingCodes = ['+1', '+1-242'];
        $country = Country::factory()->create(['phone_calling_code' => $callingCodes]);

        expect($country->phone_calling_code)->toBeArray()
            ->and($country->phone_calling_code)->toBe($callingCodes);
    });

    it('casts currencies to array', function (): void {
        $currencies = ['USD', 'EUR'];
        $country = Country::factory()->create(['currencies' => $currencies]);

        expect($country->currencies)->toBeArray()
            ->and($country->currencies)->toBe($currencies);
    });

    it('does not have timestamps', function (): void {
        $country = new Country;

        expect($country->timestamps)->toBeFalse();
    });
})->group('country', 'models');
