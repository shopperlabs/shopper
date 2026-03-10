<?php

declare(strict_types=1);

use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxRateRule;

uses(Tests\Core\TestCase::class);

describe(TaxRateRule::class, function (): void {
    it('can be created with factory', function (): void {
        $rule = TaxRateRule::factory()->create([
            'reference_type' => 'product_type',
            'reference_id' => 'virtual',
        ]);

        expect($rule->reference_type)->toBe('product_type')
            ->and($rule->reference_id)->toBe('virtual');
    });

    it('belongs to a `TaxRate`', function (): void {
        $taxRate = TaxRate::factory()->create();
        $rule = TaxRateRule::factory()->create(['tax_rate_id' => $taxRate->id]);

        expect($rule->taxRate)->toBeInstanceOf(TaxRate::class)
            ->and($rule->taxRate->id)->toBe($taxRate->id);
    });
})->group('tax', 'models');
