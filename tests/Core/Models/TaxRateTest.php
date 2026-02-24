<?php

declare(strict_types=1);

use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxRateRule;
use Shopper\Core\Models\TaxZone;

uses(Tests\TestCase::class);

describe(TaxRate::class, function (): void {
    it('can be created with factory', function (): void {
        $taxRate = TaxRate::factory()->create([
            'name' => 'VAT 20%',
            'rate' => 20.0,
            'code' => 'VAT20',
        ]);

        expect($taxRate->name)->toBe('VAT 20%')
            ->and($taxRate->rate)->toBe(20.0)
            ->and($taxRate->code)->toBe('VAT20');
    });

    it('belongs to a `TaxZone`', function (): void {
        $taxZone = TaxZone::factory()->create();
        $taxRate = TaxRate::factory()->create(['tax_zone_id' => $taxZone->id]);

        expect($taxRate->taxZone)->toBeInstanceOf(TaxZone::class)
            ->and($taxRate->taxZone->id)->toBe($taxZone->id);
    });

    it('has rules relationship', function (): void {
        $taxRate = TaxRate::factory()->create();
        TaxRateRule::factory()->create([
            'tax_rate_id' => $taxRate->id,
            'reference_type' => 'product_type',
            'reference_id' => 'standard',
        ]);
        TaxRateRule::factory()->create([
            'tax_rate_id' => $taxRate->id,
            'reference_type' => 'product_type',
            'reference_id' => 'virtual',
        ]);

        expect($taxRate->rules)->toHaveCount(2);
    });

    it('casts rate to float', function (): void {
        $taxRate = TaxRate::factory()->create(['rate' => 19.5]);

        expect($taxRate->rate)->toBeFloat()
            ->and($taxRate->rate)->toBe(19.5);
    });

    it('casts is_default to boolean', function (): void {
        $taxRate = TaxRate::factory()->create(['is_default' => 1]);

        expect($taxRate->is_default)->toBeTrue()
            ->and($taxRate->is_default)->toBeBool();
    });

    it('casts is_combinable to boolean', function (): void {
        $taxRate = TaxRate::factory()->create(['is_combinable' => 1]);

        expect($taxRate->is_combinable)->toBeTrue()
            ->and($taxRate->is_combinable)->toBeBool();
    });

    it('casts metadata to array', function (): void {
        $metadata = ['fiscal_code' => 'FR-VAT-20'];
        $taxRate = TaxRate::factory()->create(['metadata' => $metadata]);

        expect($taxRate->metadata)->toBeArray()
            ->and($taxRate->metadata)->toBe($metadata);
    });
})->group('tax', 'models');
