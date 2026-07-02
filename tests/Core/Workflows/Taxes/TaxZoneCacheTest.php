<?php

declare(strict_types=1);

use Shopper\Core\Models\Country;
use Shopper\Core\Models\TaxZone;
use Shopper\Core\Taxes\TaxCalculationContext;
use Shopper\Core\Taxes\TaxCalculator;

uses(Tests\Core\TestCase::class);

it('serves the resolved tax zone from the cache and invalidates it when a zone changes', function (): void {
    $country = Country::query()->where('cca2', 'US')->first()
        ?? Country::factory()->create(['cca2' => 'US', 'name' => 'United States']);

    $zone = TaxZone::factory()->create([
        'country_id' => $country->id,
        'is_tax_inclusive' => false,
    ]);

    $context = new TaxCalculationContext(countryCode: 'US');

    $resolved = resolve(TaxCalculator::class)->resolveZone($context);
    expect($resolved->is_tax_inclusive)->toBeFalse();

    $zone->update(['is_tax_inclusive' => true]);

    app()->forgetInstance(TaxCalculator::class);

    $fresh = resolve(TaxCalculator::class)->resolveZone($context);
    expect($fresh->is_tax_inclusive)->toBeTrue();
})->group('core', 'taxes');
