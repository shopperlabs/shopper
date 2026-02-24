<?php

declare(strict_types=1);

use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxRateRule;
use Shopper\Core\Models\TaxZone;
use Shopper\Core\Taxes\SystemTaxProvider;
use Shopper\Core\Taxes\TaxCalculationContext;
use Tests\Core\Stubs\TaxableItemStub;

uses(Tests\TestCase::class);

describe('SystemTaxProvider', function (): void {
    it('calculates VAT 20% inclusive on a 10000 cents item (France)', function (): void {
        $france = TaxZone::factory()->create([
            'name' => 'France',
            'country_code' => 'fr',
            'is_tax_inclusive' => true,
        ]);

        TaxRate::factory()->create([
            'name' => 'TVA 20%',
            'rate' => 20.0,
            'is_default' => true,
            'tax_zone_id' => $france->id,
        ]);

        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'fr');
        $item = new TaxableItemStub(amount: 10000);

        $taxLines = $provider->getTaxLines($item, $context);

        // 10000 - (10000 / 1.20) = 10000 - 8333.33 = 1666.67 → 1667
        expect($taxLines)->toHaveCount(1)
            ->and($taxLines[0]->name)->toBe('TVA 20%')
            ->and($taxLines[0]->rate)->toBe(20.0)
            ->and($taxLines[0]->amount)->toBe(1667);
    });

    it('calculates Sales Tax 8.5% exclusive on a 10000 cents item (California)', function (): void {
        $us = TaxZone::factory()->create([
            'name' => 'United States',
            'country_code' => 'us',
            'is_tax_inclusive' => false,
        ]);

        $california = TaxZone::factory()->create([
            'name' => 'California',
            'country_code' => 'us',
            'province_code' => 'CA',
            'is_tax_inclusive' => false,
            'parent_id' => $us->id,
        ]);

        TaxRate::factory()->create([
            'name' => 'CA Sales Tax',
            'rate' => 8.5,
            'is_default' => true,
            'tax_zone_id' => $california->id,
        ]);

        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'us', provinceCode: 'CA');
        $item = new TaxableItemStub(amount: 10000);

        $taxLines = $provider->getTaxLines($item, $context);

        // 10000 * 8.5 / 100 = 850
        expect($taxLines)->toHaveCount(1)
            ->and($taxLines[0]->name)->toBe('CA Sales Tax')
            ->and($taxLines[0]->rate)->toBe(8.5)
            ->and($taxLines[0]->amount)->toBe(850);
    });

    it('falls back to country zone when province zone does not exist', function (): void {
        TaxZone::factory()->create([
            'name' => 'Germany',
            'country_code' => 'de',
            'is_tax_inclusive' => true,
        ]);

        $germany = TaxZone::query()->where('country_code', 'de')->first();

        TaxRate::factory()->create([
            'name' => 'MwSt 19%',
            'rate' => 19.0,
            'is_default' => true,
            'tax_zone_id' => $germany->id,
        ]);

        $provider = new SystemTaxProvider;
        // Province code "BY" (Bavaria) doesn't have its own zone
        $context = new TaxCalculationContext(countryCode: 'de', provinceCode: 'BY');
        $item = new TaxableItemStub(amount: 5000);

        $taxLines = $provider->getTaxLines($item, $context);

        // Falls back to Germany zone: 5000 - (5000 / 1.19) = 5000 - 4201.68 = 798.32 → 798
        expect($taxLines)->toHaveCount(1)
            ->and($taxLines[0]->name)->toBe('MwSt 19%')
            ->and($taxLines[0]->amount)->toBe(798);
    });

    it('returns empty when no zone matches the context', function (): void {
        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'xx');
        $item = new TaxableItemStub(amount: 10000);

        $taxLines = $provider->getTaxLines($item, $context);

        expect($taxLines)->toBeEmpty();
    });

    it('returns empty when zone exists but has no default rate', function (): void {
        TaxZone::factory()->create([
            'name' => 'Japan',
            'country_code' => 'jp',
            'is_tax_inclusive' => true,
        ]);

        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'jp');
        $item = new TaxableItemStub(amount: 10000);

        $taxLines = $provider->getTaxLines($item, $context);

        expect($taxLines)->toBeEmpty();
    });

    it('applies a product type override rate instead of the default', function (): void {
        $uk = TaxZone::factory()->create([
            'name' => 'United Kingdom',
            'country_code' => 'gb',
            'is_tax_inclusive' => true,
        ]);

        $standardRate = TaxRate::factory()->create([
            'name' => 'Standard VAT',
            'rate' => 20.0,
            'is_default' => true,
            'tax_zone_id' => $uk->id,
        ]);

        $reducedRate = TaxRate::factory()->create([
            'name' => 'Reduced VAT',
            'rate' => 5.0,
            'is_default' => false,
            'tax_zone_id' => $uk->id,
        ]);

        TaxRateRule::factory()->create([
            'tax_rate_id' => $reducedRate->id,
            'reference_type' => 'product_type',
            'reference_id' => 'virtual',
        ]);

        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'gb');

        // Standard product → default 20% rate
        $standardItem = new TaxableItemStub(amount: 12000, productType: 'standard');
        $standardLines = $provider->getTaxLines($standardItem, $context);

        expect($standardLines)->toHaveCount(1)
            ->and($standardLines[0]->name)->toBe('Standard VAT')
            ->and($standardLines[0]->rate)->toBe(20.0);

        // Virtual product → reduced 5% rate
        $virtualItem = new TaxableItemStub(amount: 12000, productType: 'virtual');
        $virtualLines = $provider->getTaxLines($virtualItem, $context);

        // 12000 - (12000 / 1.05) = 12000 - 11428.57 = 571.43 → 571
        expect($virtualLines)->toHaveCount(1)
            ->and($virtualLines[0]->name)->toBe('Reduced VAT')
            ->and($virtualLines[0]->rate)->toBe(5.0)
            ->and($virtualLines[0]->amount)->toBe(571);
    });

    it('multiplies by quantity for multi-unit orders', function (): void {
        $france = TaxZone::factory()->create([
            'name' => 'France Multi',
            'country_code' => 'fr',
            'province_code' => 'IDF',
            'is_tax_inclusive' => false,
        ]);

        TaxRate::factory()->create([
            'name' => 'TVA 20%',
            'rate' => 20.0,
            'is_default' => true,
            'tax_zone_id' => $france->id,
        ]);

        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'fr', provinceCode: 'IDF');
        // 3 items at 5000 cents each
        $item = new TaxableItemStub(amount: 5000, quantity: 3);

        $taxLines = $provider->getTaxLines($item, $context);

        // (5000 * 3) * 20 / 100 = 15000 * 0.20 = 3000
        expect($taxLines)->toHaveCount(1)
            ->and($taxLines[0]->amount)->toBe(3000);
    });

    it('calculates 0% tax correctly', function (): void {
        $oregon = TaxZone::factory()->create([
            'name' => 'Oregon',
            'country_code' => 'us',
            'province_code' => 'OR',
            'is_tax_inclusive' => false,
        ]);

        TaxRate::factory()->create([
            'name' => 'No Sales Tax',
            'rate' => 0.0,
            'is_default' => true,
            'tax_zone_id' => $oregon->id,
        ]);

        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'us', provinceCode: 'OR');
        $item = new TaxableItemStub(amount: 10000);

        $taxLines = $provider->getTaxLines($item, $context);

        expect($taxLines)->toHaveCount(1)
            ->and($taxLines[0]->amount)->toBe(0);
    });
})->group('workflows', 'tax');
