<?php

declare(strict_types=1);

use Shopper\Core\Models\Country;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxRateRule;
use Shopper\Core\Models\TaxZone;
use Shopper\Core\Taxes\SystemTaxProvider;
use Shopper\Core\Taxes\TaxCalculationContext;
use Shopper\Core\Taxes\TaxCalculator;
use Tests\Core\Stubs\TaxableItemStub;

uses(Tests\TestCase::class);

describe('SystemTaxProvider', function (): void {
    it('calculates VAT 20% inclusive on a 10000 cents item (France)', function (): void {
        $france = Country::query()->where('cca2', 'FR')->first();

        $zone = TaxZone::factory()->create([
            'country_id' => $france->id,
            'is_tax_inclusive' => true,
        ]);

        TaxRate::factory()->create([
            'name' => 'TVA 20%',
            'rate' => 20.0,
            'is_default' => true,
            'tax_zone_id' => $zone->id,
        ]);

        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'FR', resolvedZone: $zone);
        $item = new TaxableItemStub(amount: 10000);

        $taxLines = $provider->getTaxLines($item, $context);

        // 10000 - (10000 / 1.20) = 10000 - 8333.33 = 1666.67 → 1667
        expect($taxLines)->toHaveCount(1)
            ->and($taxLines[0]->name)->toBe('TVA 20%')
            ->and($taxLines[0]->rate)->toBe(20.0)
            ->and($taxLines[0]->amount)->toBe(1667);
    });

    it('calculates Sales Tax 8.5% exclusive on a 10000 cents item (California)', function (): void {
        $us = Country::query()->where('cca2', 'US')->first();

        $usZone = TaxZone::factory()->create([
            'country_id' => $us->id,
            'is_tax_inclusive' => false,
        ]);

        $california = TaxZone::factory()->create([
            'country_id' => $us->id,
            'province_code' => 'US-CA',
            'name' => 'California',
            'is_tax_inclusive' => false,
            'parent_id' => $usZone->id,
        ]);

        TaxRate::factory()->create([
            'name' => 'CA Sales Tax',
            'rate' => 8.5,
            'is_default' => true,
            'tax_zone_id' => $california->id,
        ]);

        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'US', provinceCode: 'US-CA', resolvedZone: $california);
        $item = new TaxableItemStub(amount: 10000);

        $taxLines = $provider->getTaxLines($item, $context);

        // 10000 * 8.5 / 100 = 850
        expect($taxLines)->toHaveCount(1)
            ->and($taxLines[0]->name)->toBe('CA Sales Tax')
            ->and($taxLines[0]->rate)->toBe(8.5)
            ->and($taxLines[0]->amount)->toBe(850);
    });

    it('returns empty when resolved zone is null', function (): void {
        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'XX');
        $item = new TaxableItemStub(amount: 10000);

        $taxLines = $provider->getTaxLines($item, $context);

        expect($taxLines)->toBeEmpty();
    });

    it('returns empty when zone exists but has no default rate', function (): void {
        $japan = Country::query()->where('cca2', 'JP')->first();

        $zone = TaxZone::factory()->create([
            'country_id' => $japan->id,
            'is_tax_inclusive' => true,
        ]);

        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'JP', resolvedZone: $zone);
        $item = new TaxableItemStub(amount: 10000);

        $taxLines = $provider->getTaxLines($item, $context);

        expect($taxLines)->toBeEmpty();
    });

    it('applies a product type override rate instead of the default', function (): void {
        $uk = Country::query()->where('cca2', 'GB')->first();

        $zone = TaxZone::factory()->create([
            'country_id' => $uk->id,
            'is_tax_inclusive' => true,
        ]);

        TaxRate::factory()->create([
            'name' => 'Standard VAT',
            'rate' => 20.0,
            'is_default' => true,
            'tax_zone_id' => $zone->id,
        ]);

        $reducedRate = TaxRate::factory()->create([
            'name' => 'Reduced VAT',
            'rate' => 5.0,
            'is_default' => false,
            'tax_zone_id' => $zone->id,
        ]);

        TaxRateRule::factory()->create([
            'tax_rate_id' => $reducedRate->id,
            'reference_type' => 'product_type',
            'reference_id' => 'virtual',
        ]);

        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'GB', resolvedZone: $zone);

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
        $france = Country::query()->where('cca2', 'FR')->first();

        $zone = TaxZone::factory()->create([
            'country_id' => $france->id,
            'province_code' => 'FR-IDF',
            'name' => 'Île-de-France',
            'is_tax_inclusive' => false,
        ]);

        TaxRate::factory()->create([
            'name' => 'TVA 20%',
            'rate' => 20.0,
            'is_default' => true,
            'tax_zone_id' => $zone->id,
        ]);

        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'FR', provinceCode: 'FR-IDF', resolvedZone: $zone);
        // 3 items at 5000 cents each
        $item = new TaxableItemStub(amount: 5000, quantity: 3);

        $taxLines = $provider->getTaxLines($item, $context);

        // (5000 * 3) * 20 / 100 = 15000 * 0.20 = 3000
        expect($taxLines)->toHaveCount(1)
            ->and($taxLines[0]->amount)->toBe(3000);
    });

    it('calculates 0% tax correctly', function (): void {
        $us = Country::query()->where('cca2', 'US')->first();

        $zone = TaxZone::factory()->create([
            'country_id' => $us->id,
            'province_code' => 'US-OR',
            'name' => 'Oregon',
            'is_tax_inclusive' => false,
        ]);

        TaxRate::factory()->create([
            'name' => 'No Sales Tax',
            'rate' => 0.0,
            'is_default' => true,
            'tax_zone_id' => $zone->id,
        ]);

        $provider = new SystemTaxProvider;
        $context = new TaxCalculationContext(countryCode: 'US', provinceCode: 'US-OR', resolvedZone: $zone);
        $item = new TaxableItemStub(amount: 10000);

        $taxLines = $provider->getTaxLines($item, $context);

        expect($taxLines)->toHaveCount(1)
            ->and($taxLines[0]->amount)->toBe(0);
    });
});

describe('TaxCalculator', function (): void {
    it('falls back to country zone when province zone does not exist', function (): void {
        $germany = Country::query()->where('cca2', 'DE')->first();

        $zone = TaxZone::factory()->create([
            'country_id' => $germany->id,
            'is_tax_inclusive' => true,
        ]);

        TaxRate::factory()->create([
            'name' => 'MwSt 19%',
            'rate' => 19.0,
            'is_default' => true,
            'tax_zone_id' => $zone->id,
        ]);

        $calculator = resolve(TaxCalculator::class);
        // Province code "BY" (Bavaria) doesn't have its own zone
        $context = new TaxCalculationContext(countryCode: 'DE', provinceCode: 'BY');
        $item = new TaxableItemStub(amount: 5000);

        $taxLines = $calculator->calculate($item, $context);

        // Falls back to Germany zone: 5000 - (5000 / 1.19) = 5000 - 4201.68 = 798.32 → 798
        expect($taxLines)->toHaveCount(1)
            ->and($taxLines[0]->name)->toBe('MwSt 19%')
            ->and($taxLines[0]->amount)->toBe(798);
    });

    it('resolves province zone over country zone', function (): void {
        $us = Country::query()->where('cca2', 'US')->first();

        TaxZone::factory()->create([
            'country_id' => $us->id,
            'is_tax_inclusive' => false,
        ]);

        $california = TaxZone::factory()->create([
            'country_id' => $us->id,
            'province_code' => 'US-CA',
            'name' => 'California',
            'is_tax_inclusive' => false,
        ]);

        TaxRate::factory()->create([
            'name' => 'CA Sales Tax',
            'rate' => 7.25,
            'is_default' => true,
            'tax_zone_id' => $california->id,
        ]);

        $calculator = resolve(TaxCalculator::class);
        $context = new TaxCalculationContext(countryCode: 'US', provinceCode: 'US-CA');

        $resolvedZone = $calculator->resolveZone($context);

        expect($resolvedZone)->not->toBeNull()
            ->and($resolvedZone->province_code)->toBe('US-CA');
    });
})->group('workflows', 'tax');
