<?php

declare(strict_types=1);

use Shopper\Core\Models\TaxProvider;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxZone;

uses(Tests\TestCase::class);

describe(TaxZone::class, function (): void {
    it('can be created with factory', function (): void {
        $taxZone = TaxZone::factory()->create([
            'name' => 'France',
            'country_code' => 'fr',
            'is_tax_inclusive' => true,
        ]);

        expect($taxZone->name)->toBe('France')
            ->and($taxZone->country_code)->toBe('fr')
            ->and($taxZone->is_tax_inclusive)->toBeTrue();
    });

    it('has rates relationship', function (): void {
        $taxZone = TaxZone::factory()->create();
        TaxRate::factory()->count(3)->create(['tax_zone_id' => $taxZone->id]);

        expect($taxZone->rates)->toHaveCount(3);
    });

    it('has parent relationship', function (): void {
        $parent = TaxZone::factory()->create([
            'name' => 'United States',
            'country_code' => 'us',
        ]);

        $child = TaxZone::factory()->create([
            'name' => 'California',
            'country_code' => 'us',
            'province_code' => 'CA',
            'parent_id' => $parent->id,
        ]);

        expect($child->parent)->toBeInstanceOf(TaxZone::class)
            ->and($child->parent->id)->toBe($parent->id);
    });

    it('has children relationship', function (): void {
        $parent = TaxZone::factory()->create([
            'name' => 'United States',
            'country_code' => 'us',
        ]);

        TaxZone::factory()->count(2)->create([
            'country_code' => 'us',
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
})->group('tax', 'models');
