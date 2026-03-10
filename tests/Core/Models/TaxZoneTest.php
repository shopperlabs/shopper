<?php

declare(strict_types=1);

use Shopper\Core\Models\Country;
use Shopper\Core\Models\TaxProvider;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxZone;

uses(Tests\Core\TestCase::class);

describe(TaxZone::class, function (): void {
    it('can be created with factory', function (): void {
        $france = Country::query()->where('cca2', 'FR')->first();

        $taxZone = TaxZone::factory()->create([
            'country_id' => $france->id,
            'is_tax_inclusive' => true,
        ]);

        expect($taxZone->country_id)->toBe($france->id)
            ->and($taxZone->is_tax_inclusive)->toBeTrue();
    });

    it('has country relationship', function (): void {
        $germany = Country::query()->where('cca2', 'DE')->first();
        $taxZone = TaxZone::factory()->create(['country_id' => $germany->id]);

        expect($taxZone->country)->toBeInstanceOf(Country::class)
            ->and($taxZone->country->name)->toBe($germany->name)
            ->and($taxZone->country->cca2)->toBe('DE');
    });

    it('has rates relationship', function (): void {
        $taxZone = TaxZone::factory()->create();
        TaxRate::factory()->count(3)->create(['tax_zone_id' => $taxZone->id]);

        expect($taxZone->rates)->toHaveCount(3);
    });

    it('has parent relationship', function (): void {
        $us = Country::query()->where('cca2', 'US')->first();

        $parent = TaxZone::factory()->create(['country_id' => $us->id]);

        $child = TaxZone::factory()->create([
            'country_id' => $us->id,
            'province_code' => 'US-CA',
            'name' => 'California',
            'parent_id' => $parent->id,
        ]);

        expect($child->parent)->toBeInstanceOf(TaxZone::class)
            ->and($child->parent->id)->toBe($parent->id);
    });

    it('has children relationship', function (): void {
        $us = Country::query()->where('cca2', 'US')->first();

        $parent = TaxZone::factory()->create(['country_id' => $us->id]);

        TaxZone::factory()->count(2)->sequence(
            ['province_code' => 'US-CA', 'name' => 'California'],
            ['province_code' => 'US-NY', 'name' => 'New York'],
        )->create([
            'country_id' => $us->id,
            'parent_id' => $parent->id,
        ]);

        expect($parent->children)->toHaveCount(2);
    });

    it('has provider relationship', function (): void {
        $provider = TaxProvider::factory()->create(['identifier' => 'system']);
        $taxZone = TaxZone::factory()->create(['provider_id' => $provider->id]);

        expect($taxZone->provider)->toBeInstanceOf(TaxProvider::class)
            ->and($taxZone->provider->identifier)->toBe('system');
    });

    it('casts is_tax_inclusive to boolean', function (): void {
        $taxZone = TaxZone::factory()->create(['is_tax_inclusive' => 1]);

        expect($taxZone->is_tax_inclusive)->toBeTrue()
            ->and($taxZone->is_tax_inclusive)->toBeBool();
    });

    it('casts metadata to array', function (): void {
        $metadata = ['key' => 'value'];
        $taxZone = TaxZone::factory()->create(['metadata' => $metadata]);

        expect($taxZone->metadata)->toBeArray()
            ->and($taxZone->metadata)->toBe($metadata);
    });

    it('can create inclusive factory state', function (): void {
        $taxZone = TaxZone::factory()->inclusive()->create();

        expect($taxZone->is_tax_inclusive)->toBeTrue();
    });

    it('stores nullable name for province zones', function (): void {
        $us = Country::query()->where('cca2', 'US')->first();

        $countryZone = TaxZone::factory()->create([
            'country_id' => $us->id,
        ]);

        $provinceZone = TaxZone::factory()->create([
            'country_id' => $us->id,
            'province_code' => 'US-CA',
            'name' => 'California',
            'parent_id' => $countryZone->id,
        ]);

        expect($countryZone->name)->toBeNull()
            ->and($provinceZone->name)->toBe('California')
            ->and($provinceZone->province_code)->toBe('US-CA');
    });
})->group('tax', 'models');
