<?php

declare(strict_types=1);

use Shopper\Contracts\ProductSection;
use Shopper\Core\Enum\ProductType;
use Shopper\Enum\FeatureState;
use Shopper\Enum\ProductSectionGroup;
use Shopper\Navigation\Product\ProductSectionManager;
use Shopper\Navigation\Product\Sections\AttributesSection;
use Shopper\Navigation\Product\Sections\PricingSection;
use Shopper\Navigation\Product\Sections\VariantsSection;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->manager = resolve(ProductSectionManager::class);
});

function visibleSectionsFor(ProductType $type): array
{
    $product = Product::factory()->create(['type' => $type]);

    return resolve(ProductSectionManager::class)
        ->forProduct($product)
        ->map(fn (ProductSection $section): string => class_basename($section))
        ->all();
}

it('shows the right sections for a standard product', function (): void {
    expect(visibleSectionsFor(ProductType::Standard))->toBe([
        'OverviewSection',
        'MediaSection',
        'AttributesSection',
        'InventorySection',
        'PricingSection',
        'ShippingSection',
        'SeoSection',
        'RelatedProductsSection',
    ]);
})->group('products');

it('hides pricing and shows variants for a variant product', function (): void {
    expect(visibleSectionsFor(ProductType::Variant))->toBe([
        'OverviewSection',
        'MediaSection',
        'AttributesSection',
        'VariantsSection',
        'InventorySection',
        'ShippingSection',
        'SeoSection',
        'RelatedProductsSection',
    ]);
})->group('products');

it('shows files and hides shipping/variants for a virtual product', function (): void {
    expect(visibleSectionsFor(ProductType::Virtual))->toBe([
        'OverviewSection',
        'MediaSection',
        'AttributesSection',
        'InventorySection',
        'PricingSection',
        'FilesSection',
        'SeoSection',
        'RelatedProductsSection',
    ]);
})->group('products');

it('hides attributes, shipping and variants for an external product', function (): void {
    expect(visibleSectionsFor(ProductType::External))->toBe([
        'OverviewSection',
        'MediaSection',
        'InventorySection',
        'PricingSection',
        'SeoSection',
        'RelatedProductsSection',
    ]);
})->group('products');

it('hides the attributes section when the attribute feature is disabled', function (): void {
    config(['shopper.features.attribute' => FeatureState::Disabled]);

    expect(visibleSectionsFor(ProductType::Standard))->not->toContain('AttributesSection');
})->group('products');

it('groups sections by section group in order', function (): void {
    $product = Product::factory()->create(['type' => ProductType::Standard]);

    $grouped = $this->manager->groupedForProduct($product);

    expect($grouped->pluck('group')->all())->toBe([
        ProductSectionGroup::Product->value,
        ProductSectionGroup::Inventory->value,
        ProductSectionGroup::Sales->value,
        ProductSectionGroup::Marketing->value,
    ]);
})->group('products');

it('filters sections by visibility for the product type', function (): void {
    $variant = Product::factory()->create(['type' => ProductType::Variant]);

    $sections = $this->manager->forProduct($variant);

    expect($sections->contains(fn (ProductSection $section): bool => $section instanceof VariantsSection))->toBeTrue()
        ->and($sections->contains(fn (ProductSection $section): bool => $section instanceof PricingSection))->toBeFalse()
        ->and($sections->contains(fn (ProductSection $section): bool => $section instanceof AttributesSection))->toBeTrue();
})->group('products');
